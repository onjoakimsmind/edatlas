<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::get('systems', 'App\Http\Controllers\SystemController@index')->name('systems.api.index');
    Route::get('systems/filter', 'App\Http\Controllers\SystemController@filter')->name('systems.api.filter');
    Route::get('systems/search', 'App\Http\Controllers\SystemController@search')->name('systems.api.search');

    Route::get('systems/{system}', 'App\Http\Controllers\SystemController@show')->name('systems.api.show');
    Route::get('systems/{system}/stations', 'App\Http\Controllers\SystemController@stations')->name('systems.api.stations');
    Route::get('systems/{system}/stations/{station}', 'App\Http\Controllers\SystemController@station')->name('systems.api.station');

    Route::get('systems/{system}/factions/', 'App\Http\Controllers\SystemController@factions')->name('systems.api.factions');
    Route::get('systems/{system}/factions/{faction}', 'App\Http\Controllers\SystemController@faction')->name('systems.api.faction');

    Route::get('systems/{system}/conflicts/', 'App\Http\Controllers\SystemController@conflicts')->name('systems.api.conflitcs');
    
    Route::get('systems/{system}/bodies', 'App\Http\Controllers\BodyController@show')->name('systems.api.bodies');

    Route::post('systems', 'App\Http\Controllers\SystemController@store');
    Route::post('systems/{system}/bodies', 'App\Http\Controllers\BodyController@store');
});