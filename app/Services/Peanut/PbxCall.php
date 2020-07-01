<?php

namespace App\Services\Peanut;

use Guzzle\Http\Client as HttpClient;

class PbxCall extends Peanut
{
    public function push($callerid, $extension)
    {
        $http = new HttpClient();

        $http = $http->post(
            $this->url . '/pbx/push-call?token=' . $this->token,
            null,
            [
                'callerid' => $callerid,
                'extension' => $extension
            ]
        )->send();

        return json_decode($http->getBody()) ?? null;
    }

}
