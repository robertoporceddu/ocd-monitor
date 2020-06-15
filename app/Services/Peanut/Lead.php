<?php

namespace App\Services\Peanut;

use Guzzle\Http\Client as HttpClient;
use stdClass;

class Lead
{
    public static function inject($url, $token, $lead)
    {
        $http = new HttpClient();

        $newLead = $lead;
        $newLead->id = $lead->ocm_status_id;
        $newLead->nominativo = $lead->customer_data_name_cli;
        $newLead->telefono = $lead->customer_data_phone_cli;
        $newLead->supplier = $lead->supplier_name;
        for($i = 1; $i < 20; $i++) {
            $newLead->{'f'.$i} = $lead->{'field_'.$i};
        }

        $http = $http->post(
            $url . "/tasks/outbound/$lead->campaign_buyer/$lead->campaign_schema/store/recall?token=$token",
            null,
            [ 'data' => json_encode($lead) ]
        )->send();

        return json_decode($http->getBody()) ?? null;
    }

}
