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

        $permissions = Auth::user()->allPermissions();

        if ( Auth::check() && (isset($permissions[$action]) ) )
        {
            return $next($request);
        }

        abort(403);
    }

}
