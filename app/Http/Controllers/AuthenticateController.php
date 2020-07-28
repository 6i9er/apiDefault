<?php

namespace App\Http\Controllers;

use App\Mail\ThanksSubscribeMail;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Mail\TestEmail;
use Illuminate\Support\Facades\Mail;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticateController extends Controller
{
    public function login(Request $request)
    {
//        return $request;
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }

    public function getUserData(Request $request){
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (\Exception $e) {

            return response()->json(['token_expired']);

        }
        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
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
            "errors" => 1,
            'msg' => $data
        ];
    }

}
