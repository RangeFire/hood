<?php

namespace App\Http\Middleware;

use Closure;
use App\Models;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use \App\Helpers\Auth;
use DateTimeImmutable;
use Illuminate\Http\Request;
use App\Scopes\CustomerScope;
use Illuminate\Support\Facades\Session;

class JWTAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle(Request $request, Closure $next)
    {

        if(!$request->bearerToken())
            return response()->json(['error' => 'Unauthorized', 'data' => null], 401);

        $secret_Key  = '68V0zWFrS72GbpPreidkQFLfj4v9m3Ti+DXc8OB0gcM=';
        $token = JWT::decode($request->bearerToken(), new Key($secret_Key, 'HS512'));
        
        $now = new DateTimeImmutable();
        $serverName = "wehood.app";
        
        if ($token->iss !== $serverName ||
            $token->nbf > $now->getTimestamp() ||
            $token->exp < $now->getTimestamp())
        {
            return response()->json(['error' => 'Forbidden', 'data' => null], 403);
        }

        $user = Models\User::where('username', $token->userName)->first();

        // print_r($user->toArray());

        if(!$user)
            return response()->json(['error' => 'Forbidden', 'data' => null], 403);

        /* assigns userdata for future uses (scopes, ...) */
        Auth::setUser($user);

        return $next($request);
    }

}
