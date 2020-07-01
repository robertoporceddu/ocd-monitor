<?php

namespace App\Services\Peanut;

use Carbon\Carbon;
use Guzzle\Http\Client as HttpClient;
use stdClass;

class InjectionLog extends Peanut
{
    public function getLatestContactsPerBuyerByPhone($phone, $buyer, $exclude_id = null, $take = 25)
    {
        $http = new HttpClient();

        $http = $http->get(
            $this->url . '/logs/injection?' . 
                'q=['.
                    '["where","customer_data_phone_cli","=","' . $phone . '"],' .
                    '["and","campaign_buyer","=","' . $buyer . '"]' .
                    ($exclude_id ? ',["and","id","!=","' . $exclude_id . '"]' : '') .
                ']'.
                '&o=[["updated_at","desc"]]'.
                '&per_page='. $take .
                '&token=' . $this->token
        )->send();

        if ($response = json_decode($http->getBody())) {
            if($response->code == 200 or $response->code == 204) {
                return collect($response->payload->data);
            } else {
                throw new \Exception($response->status,$response->code);
            }
        } else {
            return null;
        }
    }

    public function getLastContactByPhone($phone)
    {
        $http = new HttpClient();

        $http = $http->get(
            $this->url . '/logs/injection?' . 
                'q=['.
                    '["whereraw","customer_data_phone_cli = \'' . $phone . '\' AND (last_outcome_user_username IS NULL OR last_outcome_user_username != \'catch.all\')"]'.
                ']'.
                '&o=[["updated_at","desc"]]'.
                '&per_page=1'.
                '&token=' . $this->token
        )->send();

        if ($response = json_decode($http->getBody())) {
            if($response->code == 200) {
                $contact = collect($response->payload->data)->first();
                $contact->__isClosed = self::isClosed($contact);
                $contact->__isOkOrNew = self::isOkOrNew($contact);

                return $contact;
            } else if($response->code == 204) {
                $contact = new stdClass;
                $contact->__isClosed = true;
                $contact->__isOkOrNew = true;

                return $contact;
            } else {
                throw new \Exception($response->status,$response->code);
            }
        } else {
            return null;
        }
    }
    
    public function isClosed($contact)
    {
        $isClosed = false;

        if($contact->last_outcome_type ?? null) {
            if (in_array($contact->last_outcome_type,['OP_OK', 'OP_KO']) or preg_match('/^BO_.+/',$contact->last_outcome_type)) {
                $isClosed = true;
            }
        }

        return $isClosed;
    }

    public function isOkOrNew($contact)
    {
        $isOkOrNew = false;

        if($contact->last_outcome_type ?? null) {
            if ($contact->last_outcome_type == 'OP_OK' or preg_match('/^BO_.+/',$contact->last_outcome_type)) {
                $isOkOrNew = true;
            }
        } else {
            $isOkOrNew = true;
        }

        return $isOkOrNew;
    }

    public function waitInjection($phone, $searches = 15, $time = 2)
    {
        $http = new HttpClient();
        $lead = collect([]);

        $searches = 0;
        while($searches < 15 and !$lead->count()) {
            $response = $http->get(
                $this->url . '/logs/injection?' . 
                    'q=['.
                        '["where","customer_data_phone_cli","=","' . $phone . '"],' .
                        '["wherenull","first_charge_user_username"],' .
                        '["between","created_at",[' .
                            '"' . Carbon::now()->subMinutes(2)->toDateTimeString() . '",' .
                            '"' . Carbon::now()->addMinutes(2)->toDateTimeString() . '"' .
                        ']]' .
                    ']' .
                    '&o=[["updated_at","desc"]]' .
                    '&per_page=1' .
                    '&token=' . $this->token
            )->send();

            if (json_decode($response->getBody())) {
                $response = json_decode($response->getBody());
                
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
