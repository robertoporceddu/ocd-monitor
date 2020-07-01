<?php

namespace App\Services\Ocm;

use Guzzle\Http\Client as HttpClient;

class Lead extends Ocm
{
    public function inject($sap, $data)
    {
        $http = new HttpClient();

        $http = $http->post(
            $this->url . '/injection-feed/'. $sap .'/injects',
            [ 'OCM-ApiKey-Token ' => $this->token ],
            $data
        )->send();

        return json_decode($http->getBody()) ?? null;
    }

}
