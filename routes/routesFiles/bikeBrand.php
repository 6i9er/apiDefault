<?php

Route::get('bike-brands' , 'BikeBrandController@index');
Route::get('delete-bike-brand/{uuid?}/{lang?}' , 'BikeBrandController@deleteBikeBrand');
Route::post('save-bike-brand' , 'BikeBrandController@saveBikeBrand');