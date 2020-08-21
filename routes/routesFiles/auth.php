<?php

Route::post('login' , 'AuthenticateController@login');
Route::post('signup' , 'AuthenticateController@signup');
Route::post('reset-password' , 'AuthenticateController@resetPassword');
Route::post('login-by-api' , 'AuthenticateController@loginByAPI');