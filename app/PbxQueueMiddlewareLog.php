<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PbxQueueMiddlewareLog extends Model
{
    protected $fillable = [
        'api_method',
        'from_user',
        'from_ip',
        'callerid',
        'fromid',
        'extension',
        'error_code',
        'error_message'
    ];

    public static function error($api_method, $request, $error_code = '', $error_message = '')
    {
        $callerid = $request->input('callerid');
        $to_mask = substr($callerid, strlen($callerid)-3, strlen($callerid));
        for($mask = '' or $i = 0; $i <= strlen($to_mask); $i++ and $mask .= '*');
        $callerid = preg_replace('/'.$to_mask.'$/', $mask, $callerid);

        $token = $request->header(env('API_KEY_TOKEN', 'ApiKey-Token'));
        $user = (new User())->where('client_api_token', $token)->first();

        self::insert([
            'api_method' => $api_method,
            'from_user' => $user->email,
            'from_ip' => $request->ip(),
            'callerid' => $callerid,
            'fromid' => $request->input('fromid'),
            'extension' => $request->input('extension'),
            'error_code' => $error_code,
            'error_message' => $error_message,
            'created_at' => Carbon::now()
        ]);
    }
}
