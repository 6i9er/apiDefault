<?php

use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
});







//Route::post('login', array('middleware' => ['Cors' , 'api'], 'uses' => 'AuthenticateController@login'));

Route::group(
    [
        'middleware' => [ 'api', 'Cors']
    ],
    function()
    {

//        Authenticate Controller
        Route::post('login' , 'AuthenticateController@login');
        Route::post('signup' , 'AuthenticateController@signup');
        Route::post('reset-password' , 'AuthenticateController@resetPassword');
        Route::post('login-by-api' , 'AuthenticateController@loginByAPI');
//        User Controller
        Route::get('user' , 'UserController@getUserData');
        Route::get('user-with-uuid/{uuid?}/{lang?}' , 'UserController@getUserDataWithUUID');
        Route::get('block-user/{uuid?}/{lang?}' , 'UserController@blockUser');
        Route::get('unblock-user/{uuid?}/{lang?}' , 'UserController@unblockUser');
        Route::post('change-password' , 'UserController@changePassword');
        Route::post('change-settings' , 'UserController@changeSetting');



        Route::get('home' , 'AuthenticateController@home');

    });


