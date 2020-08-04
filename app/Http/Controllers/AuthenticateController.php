<?php

namespace App\Http\Controllers;

use App\Mail\SendForgetPasswordLink;
use App\Mail\ThanksSubscribeMail;
use App\Models\Article;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Mail\TestEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Webpatser\Uuid\Uuid;

class AuthenticateController extends Controller
{
    public function login(Request $request)
    {
        if($request['lang']){
            if($request['lang'] == 'en'){
                app()->setLocale("en");
            }
        }
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['errors'=>1 , 'msg' => trans('users.invalid_credentials')], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['errors'=>1 , 'msg' => trans('users.could_not_create_token')], 500);
        }

        // all good so return the token
        return response()->json(['errors' => 0 , 'msg' => compact('token')]);
    }

    public function signup(Request $request)
    {
        if($request['lang']){
            if($request['lang'] == 'en'){
                app()->setLocale("en");
            }
        }
        $validator = Validator::make($request->toArray(), [
            'name' => 'required',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password|min:6',
            'email' => 'required|email',
            'phone' => 'required',
            'gender' => 'min:0|max:2',
        ]);

        if ($validator->fails()) {
            return [
                "errors" => 1,
                'msg' => $validator->errors()->all()
            ];
        }

        $checkEmail = Member::where("email" , $request->email)->first();
        if(count($checkEmail)){
            return [
                "errors" => 1,
                "msg" => trans('users.sorryThisEmailAlreadyExist'),
            ];
        }

        $checkPhone = Member::where("phone" , $request->phone)->first();
        if(count($checkPhone)){
            return [
                "errors" => 1,
                "msg" => trans('users.sorryThisPhoneAlreadyExist'),
            ];
        }

        $password = Hash::make($request->password);
        $newSignup = Member::add([
           "password" => $password,
           "email" => $request->email,
           "name" => $request->name,
           "gender" => $request->gender,
           "phone" => $request->phone,
           "username" => trim($request->name),
        ]);

        // grab credentials from the request
        $credentials = $request->only('email', 'password');
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['errors'=>1 , 'msg' => trans('users.invalid_credentials')], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['errors'=>1 , 'msg' => trans('users.could_not_create_token')], 500);
        }

        // all good so return the token
        return response()->json(['errors' => 0 , 'msg' => ['token' => $token , 'text' => trans('users.userRegistered')]]);
    }



    public function loginByAPI(Request $request)
    {
        if($request['lang']){
            if($request['lang'] == 'en'){
                app()->setLocale("en");
            }
        }
        $validator = Validator::make($request->toArray(), [
            'name' => 'required',
            'email' => 'required|email',
            'gender' => 'min:0|max:2',
            'api_type' => 'required|min:1|max:2',
            'api_id' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                "errors" => 1,
                'msg' => $validator->errors()->all()
            ];
        }

        $getAPIAccount = array();
        $token = '';
        if($request->api_type == "1"){
            $getAPIAccount = Member::where("facebook_id" , $request->api_id)->first();
        }else{
            $getAPIAccount = Member::where("google_id" , $request->api_id)->first();
        }
        if(count($getAPIAccount)){
            try {
                if($request->api_type == "1"){
                    // attempt to verify the credentials and create a token for the user
                    if (! $token = JWTAuth::attempt(['email'=>$getAPIAccount->email , "password" => $getAPIAccount->facebook_id])) {
                        return response()->json(['errors'=>1 , 'msg' => trans('users.invalid_credentials')], 401);
                    }
                }else{
                    // attempt to verify the credentials and create a token for the user
                    if (! $token = JWTAuth::attempt(['email'=>$getAPIAccount->email , "password" => $getAPIAccount->google_id])) {
                        return response()->json(['errors'=>1 , 'msg' => trans('users.invalid_credentials')], 401);
                    }
                }

            } catch (JWTException $e) {
                // something went wrong whilst attempting to encode the token
                return response()->json(['errors'=>1 , 'msg' => trans('users.could_not_create_token')], 500);
            }
        }else{
            $checkEmail = Member::where("email" , $request->email)->first();
            if(count($checkEmail)){
                if($request->api_type == "1"){
                    Member::where("uuid" , $checkEmail->uuid)->update(["facebook_id" => $request->api_id]);
                }else{
                    Member::where("uuid" , $checkEmail->uuid)->update(["google_id" => $request->api_id]);
                }
                try {
                    if($request->api_type == "1"){
                        // attempt to verify the credentials and create a token for the user
                        if (! $token = JWTAuth::attempt(['email'=>$checkEmail->email , "password" => $checkEmail->facebook_id])) {
                            return response()->json(['errors'=>1 , 'msg' => trans('users.invalid_credentials')], 401);
                        }
                    }else{
                        if (! $token = JWTAuth::attempt(['email'=>$checkEmail->email , "password" => $checkEmail->google_id])) {
                            return response()->json(['errors'=>1 , 'msg' => trans('users.invalid_credentials')], 401);
                        }
                    }

                } catch (JWTException $e) {
                    // something went wrong whilst attempting to encode the token
                    return response()->json(['errors'=>1 , 'msg' => trans('users.could_not_create_token')], 500);
                }
            }else{
                $newUserArray = array();
                $newUserArray['name'] = $request->name;
                $newUserArray['email'] = $request->email;
                $newUserArray['gender'] = $request->gender;
                $newUserArray['username'] = '';
                $password = $request->api_id;
                $newUserArray['password'] = Hash::make($password);

                if($request->api_type == "1"){
                    $newUserArray['facebook_id'] = $request->api_id;
                    $newUserArray['google_id'] = '';
                }else{
                    $newUserArray['google_id'] = $request->api_id;
                    $newUserArray['facebook_id'] = '';
                }
                if($request->profile_pic != ""){
                    $newUserArray['profile_pic']  = time()."-".Uuid::generate()->string.".jpg";
                    file_put_contents(public_path('uploads/users/')."/".$newUserArray['profile_pic'] , file_get_contents($request->profile_pic));
                }else{
                    $newUserArray['profile_pic'] = '';
                }

                $newMember = Member::add($newUserArray);
                try {
                    // attempt to verify the credentials and create a token for the user
                    if (! $token = JWTAuth::attempt(['email'=>$newMember->email , "password" => $password])) {
                        return response()->json(['errors'=>1 , 'msg' => trans('users.invalid_credentials')], 401);
                    }
                } catch (JWTException $e) {
                    // something went wrong whilst attempting to encode the token
                    return response()->json(['errors'=>1 , 'msg' => trans('users.could_not_create_token')], 500);
                }
            }



        }

        // all good so return the token
        return response()->json(['errors' => 0 , 'msg' => ['token' => $token , 'text' => trans('users.userRegistered')]]);
    }

    public function resetPassword(Request $request)
    {
        if($request['lang']){
            if($request['lang'] == 'en'){
                app()->setLocale("en");
            }
        }
        $validator = Validator::make($request->toArray(), [
            'email' => 'required|exists:users,email',
        ]);

        if ($validator->fails()) {
            return [
                "errors" => 1,
                'msg' => $validator->errors()->all()
            ];
        }

        $time = time();
        Member::where("email" , $request->email)->update(["forget_token" => $time]);
        $checkEmail = Member::where("email" , $request->email)->first();
        if(!count($checkEmail)){
            return [
                "errors" => 1,
                "msg" => trans('users.sorryThisEmailAlreadyExist'),
            ];
        }
        $email = new SendForgetPasswordLink($checkEmail);
        $email->setData($checkEmail) ;
        Mail::to($request->email)->send($email);
        // all good so return the token
        return response()->json(['errors' => 0 , 'msg' => trans('users.ForgetPasswordLinkSentToYourEmail')]);
    }














    public function home(Request $request){
        $data['user'] = [];
            if (JWTAuth::getToken()) {
                if ($user = JWTAuth::parseToken()->authenticate()) {
                    $data['user'] = $user;
                }
            }
            $data['articles'] = Article::all();

        return [
            "errors" => 0,
            'msg' => $data
        ];
    }

}
