<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SearchController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'v1'], function () {
    Route::group([
        'middleware' => 'api',
        'prefix' => 'auth'
    ], function ($router) {
        Route::post('register', 'App\Http\Controllers\Api\AuthController@createUser');
        Route::post('login', 'App\Http\Controllers\Api\AuthController@loginUser');
    });

    Route::get('search', SearchController::class)->name('search.api');
    Route::post('eddn', 'App\Http\Controllers\EDDN@receiver')->name('eddn.receiver');

    Route::get('galaxy', 'App\Http\Controllers\GalaxyController@index')->name('galaxy.api');
    Route::get('galaxy/scroll', 'App\Http\Controllers\GalaxyController@scroll')->name('galaxy.api.scroll');
    Route::get('galaxy/grid', 'App\Http\Controllers\GalaxyController@grid')->name('galaxy.api.grid');
    Route::get('galaxy/test', 'App\Http\Controllers\GalaxyController@galaxy')->name('galaxy.api.test');
});

require __DIR__.'/systems.php';
require __DIR__.'/stations.php';