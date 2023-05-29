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

    Route::get('systems', 'App\Http\Controllers\SystemController@index')->name('systems.api.index');
    Route::get('systems/filter', 'App\Http\Controllers\SystemController@filter')->name('systems.api.filter');
    Route::get('systems/search', 'App\Http\Controllers\SystemController@search')->name('systems.api.search');
    //Route::get('systems/search', SearchController::class)->name('systems.api.search');
    Route::get('systems/{name}', 'App\Http\Controllers\SystemController@show')->name('systems.api.show');
    Route::get('systems/search', 'App\Http\Controllers\SystemController@search')->name('systems.api.search');
    Route::get('systems/{system}/stations/{name}', 'App\Http\Controllers\SystemController@station');
    Route::get('systems/{system}/stations', 'App\Http\Controllers\SystemController@stations');
    Route::get('systems/{system}/factions/{name}', 'App\Http\Controllers\SystemController@faction');
    Route::get('systems/{system}/factions/', 'App\Http\Controllers\SystemController@factions');
    Route::get('systems/{system}/conflicts/', 'App\Http\Controllers\SystemController@conflicts');
    Route::post('systems', 'App\Http\Controllers\SystemController@store');
    Route::get('systems/{system}/bodies', 'App\Http\Controllers\BodyController@show');
    Route::post('systems/{system}/bodies', 'App\Http\Controllers\BodyController@store');

    Route::get('stations', 'App\Http\Controllers\StationController@index');
    Route::get('stations/{market_id}', 'App\Http\Controllers\StationController@show');
    Route::post('stations/{market_id}/market', 'App\Http\Controllers\StationController@storeMarket');
    Route::post('stations', 'App\Http\Controllers\StationController@store');

    Route::post('market/routes', 'App\Http\Controllers\MarketController@trade');

    Route::get('search', SearchController::class)->name('search.api');

    Route::post('eddn', 'App\Http\Controllers\EDDN@receiver')->name('eddn.receiver');
});