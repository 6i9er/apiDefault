<?php

Route::get('bike-sub-brands' , 'BikeSubBrandController@index');
Route::get('delete-bike-sub-brand/{uuid?}/{lang?}' , 'BikeSubBrandController@deleteBikeSubBrand');
Route::post('save-bike-sub-brand' , 'BikeSubBrandController@saveBikeSubBrand');