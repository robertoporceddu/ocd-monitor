<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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

        $permissions = Auth::user()->allPermissions();

        if ( Auth::check() && (isset($permissions[$action]) && $permissions[$action] == TRUE ) )
        {
            return $next($request);
        }

        if($request->ajax()){
            return response()->json([
                'code' => 403,
                'message' =>  trans('errors.403')
            ]);
        }

        abort(403);
    }

}
