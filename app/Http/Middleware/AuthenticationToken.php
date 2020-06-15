<?php

namespace App\Http\Middleware;

use Closure;
use Mmrp\Swissarmyknife\Models\Rbac\User;

class AuthenticationToken
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
        $token = $request->header(env('API_KEY_TOKEN', 'ApiKey-Token'));

        if(empty($token)) {
            return $this->tokenMissingResponse();
        }

        $user = (new User())->where('client_api_token', $token)->first();

        if(empty($user)) {
            return $this->tokenInvalidResponse();
        }

        return $next($request);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    private function tokenMissingResponse()
    {
        return response()->json([
            'code' => 403,
            'message' => 'Forbidden, Token Missing',
        ], 403);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    private function tokenInvalidResponse()
    {
        return response()->json([
            'code' => 403,
            'message' => 'Forbidden',
        ], 403);
    }
}
