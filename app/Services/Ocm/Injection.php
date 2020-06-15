<?php

namespace App\Services\Ocm;

use Guzzle\Http\Client as HttpClient;

class Injection
{
    public static function inject($url, $token, $sap, $data)
    {
        $http = new HttpClient();

        $http = $http->post(
            $url . '/injection-feed/'. $sap .'/injects',
            [ 'OCM-ApiKey-Token ' => $token ],
            $data
        )->send();

        return json_decode($http->getBody()) ?? null;
    }

}
