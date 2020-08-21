<?php

Route::get('user' , 'UserController@getUserData');
Route::get('user-with-uuid/{uuid?}/{lang?}' , 'UserController@getUserDataWithUUID');
Route::get('block-user/{uuid?}/{lang?}' , 'UserController@blockUser');
Route::get('unblock-user/{uuid?}/{lang?}' , 'UserController@unblockUser');
Route::post('change-password' , 'UserController@changePassword');
Route::post('change-settings' , 'UserController@changeSetting');