<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (empty($request->header('client_id')) || empty($request->header())){
            throw new AuthenticationException();
        }

        $user = User::query()->whereClientId($request->header('client_id'))->firstOrFail();

        if (!Hash::check($request->header('token'), $user->token)){
            throw new AuthenticationException();
        }

        return $next($request);
    }
}
