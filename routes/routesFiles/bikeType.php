<?php

Route::get('bike-types' , 'BikeTypeController@index');
Route::get('delete-bike-type/{uuid?}/{lang?}' , 'BikeTypeController@deleteBikeType');
Route::post('save-bike-type' , 'BikeTypeController@saveBikeType');