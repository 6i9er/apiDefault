<?php

namespace App\Http\Controllers;

use App\Mail\ThanksSubscribeMail;
use App\Models\Member;
use App\User;
use Illuminate\Http\Request;
use App\Mail\TestEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth' , ['except' => ["saveForgetPassword" , "forgetPassword"]]);
    }


    public function forgetPassword($uuid = '' , $email = '' , $forget_token = 'aaa'){
        $getMember =  Member::where("uuid" , $uuid)->where("email" , $email)->where("forget_token" , $forget_token)->first();
        if(!count($getMember)){
            return view('errors.404');
        }
        return view("forgetPassword/index" )->with("member" , $getMember);

    }

    public function saveForgetPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'memberEmail' => 'required|email|exists:users,email',
            'memberUUID' => 'required|exists:users,uuid',
            'memberNewPassword' => 'required|min:6',
            'memberConfirmNewPassword' => 'required|min:6|same:memberNewPassword',
        ]);
        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator->errors()->all());
        }

        $getMember = Member::where("uuid" , $request->memberUUID)->where("email" , $request->memberEmail)->first();
        if(!count($getMember)){
            return back()
                ->withInput()
                ->withErrors([ trans('users.noUserFoundWithThisData')]);
        }
        Member::edit([
            "password" => Hash::make($request->memberNewPassword),
            "forget_token" => ''
        ] , $getMember->uuid);
        return Redirect::to(env("site-online"));
    }

    public function getUserData(Request $request){
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['errors' => 1 , 'msg'=> 'trans.noUserFoundWithThisData'], 404);
            }

        } catch (\Exception $e) {

            return response()->json(['errors' => 1 , 'msg'=> 'trans.tokenExpired']);

        }
        // the token is valid and we have found the user via the sub claim
        return response()->json(['errors' => 0 , 'msg'=> $user]);
    }

    public function getUserDataWithUUID($uuid = ''){
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['errors' => 1 , 'msg'=> 'trans.noUserFoundWithThisData'], 404);
            }

        } catch (\Exception $e) {

            return response()->json(['errors' => 1 , 'msg'=> 'trans.tokenExpired']);

        }
        if($user->user_type != "1"){
            return [
                "errors" => 1,
                "msg" => trans('users.youDontHavePermissionToGetThisData')
            ];
        }
        $member = Member::getUserByUuId($uuid);
        if(!count($member)){
            return [
                'errors' => 1 ,
                'msg'=> 'trans.noMemberFoundWithThisData'
            ];
        }
        // the token is valid and we have found the user via the sub claim
        return response()->json(['errors' => 0 , 'msg'=> $member]);
    }




    public function blockUser($uuid = '' , $lang = 'ar' ){
        if($lang == 'en'){
            app()->setLocale("en");
        }
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['errors' => 1 , 'msg'=> 'trans.noUserFoundWithThisData'], 404);
            }

        } catch (\Exception $e) {

            return response()->json(['errors' => 1 , 'msg'=> 'trans.tokenExpired']);

        }
        if($user->user_type != "1"){
            return [
                "errors" => 1,
                "msg" => trans('users.youDontHavePermissionToGetThisData')
            ];
        }
        $member = Member::getUserByUuId($uuid);
        if(!count($member)){
            return [
                'errors' => 1 ,
                'msg'=> 'trans.noMemberFoundWithThisData'
            ];
        }
        Member::where("uuid" , $uuid)->update([
            "user_status" => 0
        ]);
        // the token is valid and we have found the user via the sub claim
        return response()->json(['errors' => 0 , 'msg'=> trans('users.userBlocked')]);
    }
    
    public function unblockUser($uuid = '' , $lang = 'ar' ){
        if($lang == 'en'){
            app()->setLocale("en");
        }
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['errors' => 1 , 'msg'=> 'trans.noUserFoundWithThisData'], 404);
            }

        } catch (\Exception $e) {

            return response()->json(['errors' => 1 , 'msg'=> 'trans.tokenExpired']);

        }
        if($user->user_type != "1"){
            return [
                "errors" => 1,
                "msg" => trans('users.youDontHavePermissionToGetThisData')
            ];
        }
        $member = Member::getUserByUuId($uuid);
        if(!count($member)){
            return [
                'errors' => 1 ,
                'msg'=> 'trans.noMemberFoundWithThisData'
            ];
        }
        Member::where("uuid" , $uuid)->update([
            "user_status" => 1
        ]);
        // the token is valid and we have found the user via the sub claim
        return response()->json(['errors' => 0 , 'msg'=> trans('users.userUnBlocked')]);
    }

    public function changePassword(Request $request ){
        if($request['lang']){
            if($request['lang'] == 'en'){
                app()->setLocale("en");
            }
        }

        $validator = Validator::make($request->all(), [
            'password' => '',
            'uuid' => 'required|exists:users,uuid',
            'newPassword' => 'required|min:6',
            'confirmNewPassword' => 'required|min:6|same:newPassword',
        ]);
        if ($validator->fails()) {
            return [
                "errors" => 1,
                "msg" => $validator->errors()->all()
            ];

        }
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['errors' => 1 , 'msg'=> 'trans.noUserFoundWithThisData'], 404);
            }

        } catch (\Exception $e) {

            return response()->json(['errors' => 1 , 'msg'=> 'trans.tokenExpired']);

        }

        $member = Member::getUserByUuId($request->uuid);
        if(!count($member)){
            return [
                'errors' => 1 ,
                'msg'=> 'trans.noMemberFoundWithThisData'
            ];
        }

        if($user->user_type != "1"){
            if($user->uuid != $member->uuid){
                return [
                    "errors" => 1,
                    "msg" => trans('users.youDontHavePermissionToGetThisData')
                ];
            }
            if(!Hash::check($request->password , $member->password ) ){
                return [
                    "errors" => 1,
                    "msg" => trans('users.invalidOldPassword')
                ];
            }
        }

        Member::where("uuid" , $request->uuid)->update([
            "password" => Hash::make($request->newPassword)
        ]);
        // the token is valid and we have found the user via the sub claim
        return response()->json(['errors' => 0 , 'msg'=> trans('users.userPasswordUpdated')]);
    }


    public function changeSetting(Request $request ){
        if($request['lang']){
            if($request['lang'] == 'en'){
                app()->setLocale("en");
            }
        }

//        return $_FILES["file"]["name"];

        $validator = Validator::make($request->all(), [
            'user_type' => '',
            'uuid' => 'required|exists:users,uuid',
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'gender' => 'required|min:0|max:2',
            'file' => 'max:6000|mimes:jpeg,jpg,png',
        ]);
        if ($validator->fails()) {
            return [
                "errors" => 1,
                "msg" => $validator->errors()->all()
            ];
        }
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['errors' => 1 , 'msg'=> 'trans.noUserFoundWithThisData'], 404);
            }

        } catch (\Exception $e) {

            return response()->json(['errors' => 1 , 'msg'=> 'trans.tokenExpired']);

        }

        $member = Member::getUserByUuId($request->uuid);
        if(!count($member)){
            return [
                'errors' => 1 ,
                'msg'=> 'trans.noMemberFoundWithThisData'
            ];
        }
        if($user->user_type != "1"){
            if($user->uuid != $member->uuid){
                return [
                    "errors" => 1,
                    "msg" => trans('users.youDontHavePermissionToGetThisData')
                ];
            }
        }
        $checkEmail = Member::where("email" , $request->email)->where("uuid" , "!=" , $request->uuid)->first();
        if(count($checkEmail)){
            return [
                "errors" => 1,
                "msg" => trans('users.sorryThisEmailAlreadyTaken')
            ];
        }
        $checkPhone = Member::where("phone" , $request->phone)->where("uuid" , "!=" , $request->uuid)->first();
        if(count($checkPhone)){
            return [
                "errors" => 1,
                "msg" => trans('users.sorryThisPhoneAlreadyTaken')
            ];
        }

        $filename = $member->profile_pic;
        if($request->file('file')){
            $filename =  uploadSingleImage($request->file('file') , "/uploads/users" , $filename , "1");
        }

        $updateArray = array();
        $updateArray['profile_pic'] = $filename;
        if($user->user_type == "1"){
            $updateArray['user_type'] = $request->user_type;
        }
        $updateArray['name'] = $request->name;
        $updateArray['email'] = $request->email;
        $updateArray['phone'] = $request->phone;
        $updateArray['gender'] = $request->gender;




        Member::where("uuid" , $request->uuid)->update($updateArray);
        // the token is valid and we have found the user via the sub claim
        return response()->json(['errors' => 0 , 'msg'=> trans('users.userDataUpdated')]);
    }
}
