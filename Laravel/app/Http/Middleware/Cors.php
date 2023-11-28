<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    public function handle($request, Closure $next)
    {
        // return $next($request)
        //   ->header("Access-Control-Allow-Origin", "*")
        //   ->header("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS")
        //   ->header("Access-Control-Allow-Headers", "X-Requested-With, Content-Type, X-Token-Auth, Authorization");

        $response = $next($request);

        $response->headers->set("Access-Control-Allow-Origin", "*");
        $response->headers->set("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS");
        $response->headers->set("Access-Control-Allow-Headers", "X-Requested-With, Content-Type, Accept, X-Token-Auth, Authorization, Application");
        return $response;
    }
}
