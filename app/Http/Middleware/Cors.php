<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
// use Illuminate\Http\Request;

class Cors
{
    public function handle($request, Closure $next) {

        $response = $next($request);

        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');
        
        return $response;

    }
}
