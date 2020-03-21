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

class UserController extends Controller
{
    public function register(Request $request){
        $rules = [
            'name'      => "required|string|max:100",
            'age'       => "required|numeric",
            'username'  => "required|string|max:20",
            'password'  => "required|string",
            'deviceId'  => "required|string",
            'fcmToken'  => "nullable",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) return Response::error(null, $validator->errors()->first(), 400);

        $usernameExists = User::where("username", $request->input("username"))->first();
        if(!!$usernameExists) return Response::error(null, "Username already taken, pick another", 400);
        
        $user = new User();
        $user->name     = $request->input("name");
        $user->age      = $request->input("age");
        $user->username = $request->input("username");
        $user->password = Hash::make($request->input("password"));
        $user->status   = User::STATUS_NEGATIVE;
        $user->save();

        $session = new Session();
        $session->user_id   = $user->id;
        $session->token     = Str::uuid();
        $session->fcm_token = $request->input("fcmToken");
        $session->device_id = $request->input("deviceId");
        $session->status    = Session::STATUS_ACTIVE;
        $session->expired_at= date("Y-m-d H:i:s", strtotime("+".env("SESSION_EXPIRE")));
        $session->save();

        $session->user      = $user;

        return Response::success($session);
    }

    public function pass(Request $request){
        $rules = [
            'meetDeviceId'  => "required",
            'longitude'     => "required|numeric",
            'latitude'      => "required|numeric",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) return Response::error(null, $validator->errors()->first(), 400);

        $user2 = Session::where("device_id", $request->input("meetDeviceId"))->latest()->first();
        if(!$user2) return Response::error(null, "Meet with unregistered user", 404);

        $pass = new UserPass();
        $pass->user_id      = $request->user->id;
        $pass->user_id2     = $user2->id;
        $pass->longitude    = $request->input("longitude");
        $pass->latitude     = $request->input("latitude");
        $pass->device_id    = $request->session->device_id;
        $pass->device_id2   = $request->input("meetDeviceId");
        $pass->pass_date    = date("Y-m-d");
        try{
            $pass->save();
        }catch(QueryException $e){
            return Response::error(null, $e->getMessage());
        }
        /* 
            TO DO
            - send notif to user if meet with positive user2
            - send notif to user2 if meet with positive user
        */
        

        return Response::success();
    }

    public function suspect(Request $request){
        $user = $request->user;

        if($user->status == User::STATUS_POSITIVE){
            return Response::error(null, "Already suspected");
        }

        $user->status = User::STATUS_POSITIVE;
        $user->save();

        /* 
            TO DO
            Send notif to all user that met with this user in last 14 days
        */

        return Response::success();
    }

    public function updateFcm(Request $request){
        $rules = [
            'fcmToken'  => "required",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) return Response::error(null, $validator->errors()->first(), 400);

        $session = $request->session;
        $session->fcm_token = $request->input("fcmToken");
        $session->save();
        return Response::success();
    }
}
