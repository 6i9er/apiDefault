<?php

Route::get('bikes' , 'BikeController@index');
Route::post('save-bike-for-sale' , 'BikeController@saveBikeForSale');
Route::post('save-bike-for-review' , 'BikeController@saveBikeForReview');
Route::get('get-user-bikes/{uuid?}/{lang?}' , 'BikeController@getUserBikes');
Route::get('get-my-bikes/{lang?}' , 'BikeController@getMyBikes');
Route::get('get-bike-for-sale/{uuid?}/{lang?}' , 'BikeController@getBikeForSale');
Route::get('get-bike-for-review/{uuid?}/{lang?}' , 'BikeController@getBikeForReview');
Route::get('publish-bike/{uuid?}/{lang?}' , 'BikeController@publishBike');
Route::get('un-publish-bike/{uuid?}/{lang?}' , 'BikeController@unPublishBike');
//Route::get('delete-bike-type/{uuid?}/{lang?}' , 'BikeTypeController@deleteBikeType');
