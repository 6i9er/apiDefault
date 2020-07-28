<?php

use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use \App\Enums\UsersEnums;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/





Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ],
    function()
    {


        foreach (File::allFiles(__DIR__ . '/routesFiles') as $route) {
            require_once $route->getPathname();
        }

        /** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/

        Route::get('/', function () {
            return Webpatser\Uuid\Uuid::generate()->string;
            return "aaaaaaaaaaaaaaaaaaaaaaaaa";
            return view('welcome');
        });

    Route::get('/users', 'HomeController@index');

        Route::get('/mina', function () {
            return "new Route";
            return view('welcome');
        });

        // changing Site Language
        Auth::routes();

    });





