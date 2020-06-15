<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PbxQueueMiddlewareSetting;
use App\Http\Requests\PushCallRequest;
use App\Http\Requests\GetQueueNumberRequest;
use App\PeanutCampaignQueueSetting;
use App\Services\Peanut\Lead as CrmLead;
use App\Services\Peanut\PbxCall as CrmPbxCall;
use App\Services\Peanut\InjectionLog as CrmInjectionLog;
use App\Services\Ocm\Injection as OcmInjection;
use Guzzle\Http\Exception\ClientErrorResponseException;

class PbxQueueMiddlewareController extends Controller
{
    public function getQueueNumber(GetQueueNumberRequest $request)
    {
        try {
            $settings = PbxQueueMiddlewareSetting::getByFromCallerId($request->input('fromid'));

            $queue_number = $settings->pbx_queue_number;

            if(
                $settings->type == 'inbound' and 
                $override = $this->getSettingsIfLastContactNotIsClosed($settings, $request->input('callerid'))
            ) {
                $queue_number = $override->pbx_queue_number;
            }

            return response()->json([ 
                'code' => 200, 
                'queue' => $queue_number
            ], 200);
        } catch (ClientErrorResponseException $e) {
            return response()->json([ 
                'code' => $e->getResponse()->getStatusCode(), 
                'message' => $e->getMessage(),
                'inserted' => false 
            ], $e->getResponse()->getStatusCode());
        } catch (\Exception $e) {
            return response()->json([ 
                'code' => 500, 
                'message' => $e->getMessage(),
                'inserted' => false 
            ], $e->getCode());
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
            $settings = PbxQueueMiddlewareSetting::getByFromCallerId($request->input('fromid'));
            
            if(empty($request->input('extension'))) {
                if(
                    $settings->type == 'inbound' and 
                    $override = $this->getSettingsIfLastContactNotIsClosed($settings, $request->input('callerid'))
                ) {
                    $settings->ocm_sap_fallback = $override->ocm_sap_fallback;
                }
                
                $this->ocmFallbackInjection($settings, $request->input('callerid'));

                return response()->json([ 
                    'code' => 200, 
                    'inserted' => true
                ], 200);
            }

            $this->{$settings->type}($settings, $request);

            CrmInjectionLog::waitInjection(
                $settings->crm_peanut_url,
                $settings->crm_peanut_token,
                $request->input('callerid')
            );

            CrmPbxCall::push(
                $settings->crm_peanut_url,
                $settings->crm_peanut_token,
                $request->input('callerid'),
                $request->input('extension')
            );

            return response()->json([ 
                'code' => 200, 
                'inserted' => true
            ], 200);
        } catch (ClientErrorResponseException $e) {
            return response()->json([ 
                'code' => $e->getResponse()->getStatusCode(), 
                'message' => $e->getMessage(),
                'inserted' => false 
            ], $e->getResponse()->getStatusCode());
        } catch (\Exception $e) {
            return response()->json([ 
                'code' => 500, 
                'message' => $e->getMessage(),
                'inserted' => false 
            ], $e->getCode());
        }
    }

    protected function getSettingsIfLastContactNotIsClosed($settings, $callerid)
    {
        $lastContact = CrmInjectionLog::getLastContactByPhone($settings->crm_peanut_url, $settings->crm_peanut_token, $callerid);

        $settings = collect([])->first();

        if(!$lastContact->__isClosed) {
            $settings = PeanutCampaignQueueSetting::getByCampaign($lastContact->campaign_schema);
        }

        return $settings;
    }

    protected function click2call($settings, $request)
    {
        $this->ocmInjection($settings, $request->input('callerid'));
    }

    protected function inbound($settings, $request)
    {
        $callerid = $request->input('callerid');

        $lastContact = CrmInjectionLog::getLastContactByPhone($settings->crm_peanut_url, $settings->crm_peanut_token, $callerid);

        if($lastContact->__isClosed) {
            $this->ocmInjection($settings, $callerid);
        } else {
            $this->peanutCrmInjection($settings, $lastContact);
        }
    }

    protected function peanutCrmInjection($settings, $contact)
    {
        CrmLead::inject(
            $settings->crm_peanut_url,
            $settings->crm_peanut_token,
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
        OcmInjection::inject(
            $settings->ocm_url,
            $settings->ocm_token,
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
