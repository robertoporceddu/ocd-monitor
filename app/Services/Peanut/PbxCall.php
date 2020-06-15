<?php

namespace App\Services\Peanut;

use Guzzle\Http\Client as HttpClient;

class PbxCall
{
    public static function push($url, $token, $callerid, $extension)
    {
        $http = new HttpClient();

        $http = $http->post(
            $url . '/pbx/push-call?token=' . $token,
            null,
            [
                'callerid' => $callerid,
                'extension' => $extension
            ]
        )->send();

        return json_decode($http->getBody()) ?? null;
    }

}
