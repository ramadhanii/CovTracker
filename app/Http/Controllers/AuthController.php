<?php

namespace App\Http\Controllers;

use App\Http\Helper\Response;
use App\Models\Session;
use App\Models\User;
use App\Models\UserPass;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request){
        $rules = [
            'username'  => "required|string|max:20",
            'password'  => "required|string",
            'deviceId'  => "required|string",
            'fcmToken'  => "nullable|string",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) return Response::error(null, $validator->errors()->first(), 400);

        
        $user = User::where("username", $request->input("username"))->first();
        if(!$user->exists) return Response::error(null, "Invalid login information.", 400);
        
        if(!Hash::check($request->input("password"), $user->password)){
            return Response::error(null, "Invalid login information.", 400);
        }

        // deactive old session
        Session::where("user_id", $user->id)->where("status", Session::STATUS_ACTIVE)->update(['status' => Session::STATUS_INACTIVE]);
        
        $session = new Session();
        $session->user_id   = $user->id;
        $session->token     = Str::uuid();
        $session->fcm_token = $request->input("fcmToken");
        $session->device_id = $request->input("deviceId");
        $session->status    = Session::STATUS_ACTIVE;
        $session->expired_at= date("Y-m-d H:i:s", strtotime("+".env("SESSION_EXPIRE")));
        $session->save();

        $session->user = $user;

        return Response::success($session);
    }

    public function session(Request $request){
        return Response::success($request->session);
    }
}
