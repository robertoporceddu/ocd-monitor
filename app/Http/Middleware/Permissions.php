<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Mmrp\Swissarmyknife\Models\Rbac\User;

class Permissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $action = str_replace('App\Http\Controllers\\','',$request->route()->getActionName());
        $user = NULL;
        $token = $request->header(env('API_KEY_TOKEN', 'ApiKey-Token'));

        if(empty($token))
        {
            if(Auth::check())
            {
                $user = Auth::user();
            }
        }
        else
        {
            $user = (new User())->where('client_api_token', $token)->first();
        }

        if($permissions = $user->allPermissions()) {
            if($user and isset($permissions[$action]))
            {
                return $next($request);
            }
        }

        if ($request->wantsJson()) {
            return response()->json([ 
                'code' => 403, 
                'message' => 'Forbidden',
            ], 403);
        } else {
            abort(403);
        }
    }

}
