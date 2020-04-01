<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckPassword
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
        if (env('PASSWORD_POLICY') == 'true') {

            $last_update = (Auth::user()->password_updated_at)
                ? Carbon::createFromFormat('Y-m-d H:i:s', Auth::user()->password_updated_at)
                : Carbon::createFromTimestamp(0)->toDateTimeString();

            if (
                is_null(Auth::user()->password_updated_at)
                or $last_update->diffInDays(Carbon::now()) >= env('PASSWORD_EXPIRATION')
            ) {
                return redirect()->action('AccountController@changePassword', ['id' => Auth::user()->id]);
            }
        }

        return $next($request);
    }
}
