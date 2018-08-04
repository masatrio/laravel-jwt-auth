<?php

namespace App\Http\Middleware;

use App\User;
use JWTAuth;
use Closure;

class checkToken
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
        // get Token header
        $getToken = JWTAuth::getToken();

        // get token from DB
        $getTokenDb = User::select('*')
                    ->where('remember_token', $getToken)
                    ->first();

        // check if token not same with token in DB
        if ($getToken != $getTokenDb['remember_token']) {
            return response()->json(['error' => 'Forbidden Access'], 403);
        }

        return $next($request);
    }
}
