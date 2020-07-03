<?php

namespace App\Http\Controllers;

use App\PbxQueueMiddlewareSetting;
use App\Http\Requests\PushCallRequest;
use App\Http\Requests\GetQueueNumberRequest;
use App\PbxQueueMiddlewareLog;
use App\PeanutField13VsPbxQueueNumberSetting;
use App\Services\Ocm\Ocm;
use App\Services\Peanut\Peanut;
use Guzzle\Http\Exception\ClientErrorResponseException;

class PbxQueueMiddlewareController extends Controller
{
    public function getQueueNumber(GetQueueNumberRequest $request)
    {
        try {
            $callerid = preg_replace('/^0039|\+39/m', '', strval($request->input('callerid')));

            $settings = PbxQueueMiddlewareSetting::getByFromCallerId($request->input('fromid'));

            $queue_number = $settings->pbx_queue_number;

            $peanut = new Peanut($settings->crm_peanut_url, $settings->crm_peanut_token);
            $lastContact = $peanut->injectionLog()->getLastContactByPhone($callerid);

            if(
                $settings->type == 'inbound' and 
                !$lastContact->__isOkOrNew and
                $override = PeanutField13VsPbxQueueNumberSetting::getByField13($lastContact->field_13)
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
            $callerid = preg_replace('/^0039|\+39/m', '', strval($request->input('callerid')));

            $settings = PbxQueueMiddlewareSetting::getByFromCallerId($request->input('fromid'));

            $peanut = new Peanut($settings->crm_peanut_url, $settings->crm_peanut_token);

            $lastContact = $peanut->injectionLog()->getLastContactByPhone($callerid);

            if(empty($request->input('extension'))) {
                $this->fallback($settings, $request, $callerid, $lastContact);
            } else {
                $this->{$settings->type}($settings, $request, $callerid, $lastContact);
            }

            $lead_new_contact = $peanut->injectionLog()->waitInjection($callerid);
            $lead_other_contacts = $peanut->injectionLog()->getLatestContactsPerBuyerByPhone(
                $lead_new_contact->customer_data_phone_cli,
                $lead_new_contact->campaign_buyer,
                $lead_new_contact->id
            );

            $peanut->lead()->copyHistory($lead_other_contacts, $lead_new_contact);
            $peanut->lead()->setKoOtherContacts($lead_other_contacts, $settings->crm_peanut_id_outcome_ko);

            if(!empty($request->input('extension'))) {
                $peanut->pbxCall()->push(
                    $callerid,
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

    protected function fallback($settings, $request, $callerid, $lastContact)
    {
        if($settings->type == 'inbound' and !$lastContact->__isOkOrNew) {
            return $this->peanutCrmFallbackInjection($settings, $lastContact);
        }
        
        return $this->ocmFallbackInjection($settings, $callerid);
    }

    protected function click2call($settings, $request, $callerid, $lastContact)
    {
        return $this->ocmInjection($settings, $callerid);
    }

    protected function inbound($settings, $request, $callerid, $lastContact)
    {
        if($lastContact->__isOkOrNew) {
            return $this->ocmInjection($settings, $callerid);
        }

        return $this->peanutCrmInjection($settings, $lastContact);
    }

    protected function peanutCrmFallbackInjection($settings, $contact)
    {
        $settings->crm_peanut_sell_campaign = $settings->crm_peanut_sell_campaign_fallback;

        return $this->peanutCrmInjection($settings, $contact);
    }

    protected function peanutCrmInjection($settings, $contact)
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

        if(!$contact->__isOkOrNew) {
            $contact->mgm = true;
        }

        $peanut = new Peanut($settings->crm_peanut_url, $settings->crm_peanut_token);

        return $peanut->lead()->inject(
            $settings->crm_peanut_sell_buyer,
            $settings->crm_peanut_sell_campaign,
            $contact
        );
    }

    protected function ocmFallbackInjection($settings, $callerid)
    {
        $settings->ocm_sap = $settings->ocm_sap_fallback;

        return $this->ocmInjection($settings, $callerid);
    }

    protected function ocmInjection($settings, $callerid)
    {
        $ocm = new Ocm($settings->ocm_url, $settings->ocm_token);

        return $ocm->lead()->inject(
            $settings->ocm_sap,
            [ 
                'name' => $settings->ocm_name,
                'surname' => $settings->ocm_surname,
                'offer' => $settings->ocm_sap,
                'phone' =>  $callerid,
                'privacy_1' => '1'
            ]
        );
    }

}
