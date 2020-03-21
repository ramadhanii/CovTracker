<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LoggingMiddleware
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
        Log::info(SESSIONID . "\t\t IP: ".$request->ip().";; Path: ".$request->getPathInfo().";; Method: [".$request->method()."];; Header: ".json_encode($request->header()).";;Data: ".json_encode($request->all()).";;");
        return $next($request);
    }
}
