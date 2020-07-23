<?php
namespace App\Http\Controllers;

use App\Services\Ocm\Ocm;
use App\PbxQueueMiddlewareLog;
use App\Services\Peanut\Peanut;
use App\PbxQueueMiddlewareSetting;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PushCallRequest;
use App\Http\Requests\GetQueueNumberRequest;
use App\PeanutField13VsPbxQueueNumberSetting;
use Guzzle\Http\Exception\ClientErrorResponseException;
use stdClass;

class PbxQueueMiddlewareController extends Controller
{
    protected $peanut;

    protected $contacts;

    protected $settings;

    protected $callerid;

    public function __construct()
    {
        $this->callerid = preg_replace('/^0039|\+39/m', '', strval(request()->input('callerid')));
        $this->contacts = new stdClass();
    }

    public function getQueueNumber(GetQueueNumberRequest $request)
    {
        try {
            $this->settings = PbxQueueMiddlewareSetting::getByFromCallerId($request->input('fromid'));
            $this->peanut = new Peanut($this->settings->crm_peanut_url, $this->settings->crm_peanut_token);
            $this->contacts->last = $this->peanut->injectionLog()->getLastContactByPhone($this->callerid);

            $queue_number = $this->settings->pbx_queue_number;
            if(
                $this->settings->type == 'inbound' and 
                !($this->contacts->last->__isOk or $this->contacts->last->__hasEmptyHistory) and
                ($this->contacts->last->field_13 ?? false) and
                $override = PeanutField13VsPbxQueueNumberSetting::getByField13($this->contacts->last->field_13)
            ) {
                $queue_number = $override->pbx_queue_number;
            }

            return response()->json([ 
                'code' => 200, 
                'queue' => intval($queue_number)
            ], 200);
        } catch (ClientErrorResponseException $e) {
            PbxQueueMiddlewareLog::error('getQueueNumber', $request, $e->getResponse()->getStatusCode(), $e->getMessage());

            return response()->json([ 
                'code' => $e->getResponse()->getStatusCode(), 
                'message' => $e->getMessage(),
                'inserted' => false 
            ], $e->getResponse()->getStatusCode());
        } catch (\Exception $e) {
            PbxQueueMiddlewareLog::error('getQueueNumber', $request, $e->getCode(), $e->getMessage());

            return response()->json([ 
                'code' => 500, 
                'message' => $e->getMessage(),
                'inserted' => false 
            ], ($e->getCode() == 0) ? 500 : $e->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pushCall(PushCallRequest $request)
    {
        try {
            //Log::info($request->input());

            $this->settings = PbxQueueMiddlewareSetting::getByFromCallerId($request->input('fromid'));
            $this->peanut = new Peanut($this->settings->crm_peanut_url, $this->settings->crm_peanut_token);
            $this->contacts->last = $this->peanut->injectionLog()->getLastContactByPhone($this->callerid);

            $isFallback = empty($request->input('extension'));

            if($isFallback) {
                if(
                    $this->settings->type == 'inbound' and
                    !($this->contacts->last->__isOk or $this->contacts->last->__hasEmptyHistory)
                ) {
                    $this->peanutCrmInjectionLastContact('fallback');
                }
                
                $this->ocmInjection('fallback');
            } else {
                $this->{$this->settings->type}();
            }

            $this->contacts->new = $this->peanut->injectionLog()->waitInjection($this->callerid);
            $this->contacts->others = $this->peanut->injectionLog()->getLatestContactsPerBuyerByPhone(
                $this->contacts->new->customer_data_phone_cli,
                $this->contacts->new->campaign_buyer,
                $this->contacts->new->id
            );

            $this->peanut->lead()->copyHistory($this->contacts->others, $this->contacts->new);
            $this->peanut->lead()->setKoOtherContacts(
                $this->contacts->others,
                $this->settings->crm_peanut_id_outcome_ko
            );

            if(!$isFallback) {
                $this->peanut->pbxCall()->push(
                    $this->callerid,
                    $request->input('extension')
                );
            }

            return response()->json([ 
                'code' => 200, 
                'inserted' => true
            ], 200);
        } catch (ClientErrorResponseException $e) {
            PbxQueueMiddlewareLog::error('pushCall', $request, $e->getResponse()->getStatusCode(), $e->getMessage());

            return response()->json([ 
                'code' => $e->getResponse()->getStatusCode(), 
                'message' => $e->getMessage(),
                'inserted' => false 
            ], $e->getResponse()->getStatusCode());
        } catch (\Exception $e) {
            PbxQueueMiddlewareLog::error('pushCall', $request, $e->getCode(), $e->getMessage());

            return response()->json([ 
                'code' => 500, 
                'message' => $e->getMessage(),
                'inserted' => false 
            ], ($e->getCode() == 0) ? 500 : $e->getCode());
        }
    }

    protected function click2call()
    {
        return $this->ocmInjection();
    }

    protected function inbound()
    {
        if($this->contacts->last->__isOk or $this->contacts->last->__hasEmptyHistory) {
            return $this->ocmInjection();
        }

        $this->contacts->last = $this->peanutAddInboundFieldsDetails($this->contacts->last);
        return $this->peanutCrmInjectionLastContact();
    }

    protected function peanutAddInboundFieldsDetails($contact)
    {
        switch($contact->last_outcome_type) {
            case 'OP_OK': { $contact->field_14 = 'cli in ok'; break; }
            case 'OP_KO': { $contact->field_14 = 'cli in ko'; break; }
            case 'OP_AVAILABLE': { $contact->field_14 = 'cli in nr'; break; }
            case 'OP_RECALL': { $contact->field_14 = 'cli in recall'; break; }
            case 'OP_INTERESSED': { $contact->field_14 = 'cli in interested'; break; }
            default: { break; }
        }

        if(preg_match('/^BO_.+/',$contact->last_outcome_type)) {
            $contact->field_14 = 'cli in ok';
        }

        if($contact->last_outcome_type == 'OP_KO' and preg_match('/invio sms/i',$contact->last_outcome_name)) {
            $contact->field_14 = 'ctc sms su nr';
        }

        if(!($contact->__isOk or $contact->__hasEmptyHistory)) {
            $contact->mgm = true;
        }

        return $contact;
    }

    protected function peanutCrmInjectionLastContact($mode = '')
    {

        $peanut = new Peanut($this->settings->crm_peanut_url, $this->settings->crm_peanut_token);

        $sell_campaign = $this->settings->crm_peanut_sell_campaign;
        if($mode == 'fallback') {
            $sell_campaign = $this->settings->crm_peanut_sell_campaign_fallback;
        }

        return $peanut->lead()->inject(
            $this->settings->crm_peanut_sell_buyer,
            $sell_campaign,
            $this->contacts->last
        );
    }

    protected function ocmInjection($mode = '')
    {
        $ocm = new Ocm($this->settings->ocm_url, $this->settings->ocm_token);

        $sap = $this->settings->ocm_sap;
        if($mode == 'fallback') {
            $sap = $this->settings->ocm_sap_fallback;
        }

        return $ocm->lead()->inject(
            $sap,
            [ 
                'name' => $this->settings->ocm_name,
                'surname' => $this->settings->ocm_surname,
                'offer' => $this->settings->ocm_sap,
                'phone' =>  $this->callerid,
                'privacy_1' => '1'
            ]
        );
    }

}
