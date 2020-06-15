<?php

namespace App\Services\Peanut;

use Carbon\Carbon;
use App\PeanutCampaignQueueSetting;
use Guzzle\Http\Client as HttpClient;
use stdClass;

class InjectionLog
{
    public static function getLastContactByPhone($url, $token, $phone)
    {
        $http = new HttpClient();

        $http = $http->get(
            $url . '/logs/injection?' . 
                'q=['.
                    '["whereraw","customer_data_phone_cli = \'' . $phone . '\' AND (last_outcome_user_username IS NULL OR last_outcome_user_username != \'catch.all\')"]'.
                ']'.
                '&o=[["updated_at","desc"]]'.
                '&per_page=1'.
                '&token=' . $token
        )->send();

        if ($response = json_decode($http->getBody())) {
            if($response->code == 200) {
                $contact = collect($response->payload->data)->first();
                $contact->__isClosed = self::isClosed($contact);

                return $contact;
            } else if($response->code == 204) {
                $contact = new stdClass;
                $contact->__isClosed = true;

                return $contact;
            } else {
                throw new \Exception($response->status,$response->code);
            }
        } else {
            return null;
        }
    }
    
    public static function isClosed($contact)
    {
        $isClosed = false;

        if($contact->last_outcome_type ?? null) {
            if (in_array($contact->last_outcome_type,['OP_OK', 'OP_KO']) or preg_match('/^BO_.+/',$contact->last_outcome_type)) {
                $isClosed = true;
            }
        }

        return $isClosed;
    }

    public static function waitInjection($url, $token, $phone, $searches = 15, $time = 2)
    {
        $http = new HttpClient();
        $lead = collect([]);

        $searches = 0;
        while($searches < 15 and !$lead->count()) {
            $http = $http->get(
                $url . '/logs/injection?' . 
                    'q=['.
                        '["where","customer_data_phone_cli","=","' . $phone . '"],' .
                        '["wherenull","first_charge_user_username"],' .
                        '["between","created_at",[' .
                            '"' . Carbon::now()->subMinutes(5)->toDateTimeString() . '",' .
                            '"' . Carbon::now()->toDateTimeString() . '"' .
                        ']' .
                    ']' .
                    '&o=[["updated_at","desc"]]' .
                    '&per_page=1' .
                    '&token=' . $token
            )->send();

            if (json_decode($http->getBody())) {
                $response = json_decode($http->getBody());
                
                if($response->code == 200 or $response->code == 204) {
                    $lead = collect($response->payload->data);
                } else {
                    throw new \Exception($response->status, $response->code);
                }
            }

            $searches++;
            if(!$lead->count()) { sleep($time); }
        }

        if(!$lead->count()) {
            throw new \Exception('InjectionLog, lead not arrived.', 404);
        }

        return $lead->first();
    }

}
