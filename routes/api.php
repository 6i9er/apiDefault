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


Route::group([
    'middleware' => ['api', 'cors'],
//    'namespace' => $this->namespace,
    'prefix' => 'api',
], function ($router) {
    //Add you routes here, for example:
    Route::post('login' , 'AuthenticateController@login');
    Route::get('user' , 'AuthenticateController@getUserData');
    Route::get('home' , 'AuthenticateController@home');
});


