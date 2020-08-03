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
