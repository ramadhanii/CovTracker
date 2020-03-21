<?php

namespace App\Http\Middleware;

use App\Http\Helper\Response;
use App\Models\Session;
use App\Models\User;
use Closure;

class AuthMiddleware
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

        if(!in_array($request->getPathInfo(), ["/user/register", '/auth/login'])){
            $token = $request->header("Authorization");
            if(!$token) return Response::error(null, "Please provide token key.");

            $session = Session::with("user")->where("token", $token)->first();
            if(!$session) return Response::error(null, "Invalid token.");

            if($session->status == Session::STATUS_INACTIVE) return Response::error(null, "Expired token key, please re-login");

            $request->merge(["session" => $session, "user" => $session->user]);
        }
        return $next($request);
    }
}
