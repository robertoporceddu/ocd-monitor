<?php
/**
 * Created by PhpStorm.
 * User: Matteo Meloni
 * Date: 28/07/2016
 * Time: 16:17
 */

namespace Mmrp\Swissarmyknife\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Logs extends Model
{
    use SoftDeletes;
    
    public $fillable = [
        'type',
        'action',
        'resource',
        'resource_id',
        'code',
        'message',
        'user_id',
        'request',
        'session',
        'file',
        'line',
        'trace'        
    ];

    public function user()
    {
        return $this->belongsTo('Mmrp\Swissarmyknife\Models\Rbac\User');
    }

    public static function newLine(\Exception $e, Request $request,  Array $data)
    {
        Logs::create([
            'type' => $data['type'],
            'action' => (isset($data['action'])) ? $data['action'] : NULL,
            'resource' => (isset($data['resource'])) ? $data['resource'] : NULL,
            'resource_id' => (isset($data['resource_id'])) ? $data['resource_id'] : NULL,
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
            'user_id' => (Auth::user()) ? Auth::user()->id : NULL,
            'request' => json_encode($request),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
    }
}