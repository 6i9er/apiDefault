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


//Route::post('login', array('middleware' => ['Cors' , 'api'], 'uses' => 'AuthenticateController@login'));

Route::group(
    [
        'middleware' => [ 'api', 'Cors']
    ],
    function()
    {
         foreach (File::allFiles(__DIR__ . '/routesFiles') as $route) {
            require_once $route->getPathname();
        }

        Route::get('home' , 'AuthenticateController@home');

    });


