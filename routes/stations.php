<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::get('stations', 'App\Http\Controllers\StationController@index');
    Route::get('stations/{market_id}', 'App\Http\Controllers\StationController@show');
    Route::post('stations/{market_id}/market', 'App\Http\Controllers\StationController@storeMarket');
    Route::post('stations', 'App\Http\Controllers\StationController@store');
});