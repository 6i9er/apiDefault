<?php

use  App\Enums\UsersEnums;
use Tymon\JWTAuth\Facades\JWTAuth;
function isAdmin(){
    if(in_array(Auth::user()->type , UsersEnums::$systemIds)){
        return true;
    }
    return false;
}


 function checkUserLogedInAndAdmin(){
    try {

        if (! $user = JWTAuth::parseToken()->authenticate()) {
            return ['errors' => 1 , 'msg'=> 'trans.noUserFoundWithThisData'];
        }

    } catch (\Exception $e) {

        return ['errors' => 1 , 'msg'=> 'trans.tokenExpired'];

    }
    if($user->user_type != "1"){
        return [
            "errors" => 1,
            "msg" => trans('users.youDontHavePermissionToGetThisData')
        ];
    }
}

function checkUserLogedIn(){
    try {

        if (! $user = JWTAuth::parseToken()->authenticate()) {
            return ['errors' => 1 , 'msg'=> 'trans.noUserFoundWithThisData'];
        }

    } catch (\Exception $e) {

        return ['errors' => 1 , 'msg'=> 'trans.tokenExpired'];

    }
    return [
        "errors" => 0,
        "msg" => $user
    ];
}

