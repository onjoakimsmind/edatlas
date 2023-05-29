<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SystemController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');


Route::get('/systems/{name}', function ($name) {
    return Inertia::render('Systems/Show/Index', [ 'name' => $name ]);    
})->name('systems.show');

Route::get('/systems/{name}/stations', function ($name) {
    return Inertia::render('Systems/Show/Station', [ 'name' => $name ]);    
})->name('systems.show.stations');

Route::get('/systems/{name}/stations/{station}', function ($name, $station) {
    return Inertia::render('Systems/Show/Station', [ 'name' => $name, 'station' => $station ]);    
})->name('systems.show.station');

Route::get('/systems/{name}/bodies', function ($name) {
    return Inertia::render('Systems/Show/Bodies', [ 'name' => $name ]);    
})->name('systems.show.bodies');

Route::get('/systems/{name}/edit', function ($name) {
    return Inertia::render('Systems/Show/Map', [ 'name' => $name ]);    
})->name('systems.show.edit');

Route::get('/systems/{name}/map', function ($name) {
    return Inertia::render('Systems/Show/Map', [ 'name' => $name ]);    
})->name('systems.show.map');

Route::get('/search/{q}', function ($q) {
    return Inertia::render('Search', ['q' => $q]);
})->name('search');

Route::get('/shipyard', function () {
    return Inertia::render('Home');
})->name('shipyard');

Route::get('/outfitting', function () {
    return Inertia::render('Home');
})->name('outfitting');

Route::get('/galaxy', function () {
    return Inertia::render('Home');
})->name('galaxy');

Route::get('/oauth', function() {
    function base64url_encode($plainText)
    {
        $base64 = base64_encode($plainText);
        $base64 = trim($base64, "=");
        $base64url = strtr($base64, '+/', '-_');
        return ($base64url);
    }

    $random = bin2hex(openssl_random_pseudo_bytes(32));
    $state = bin2hex(openssl_random_pseudo_bytes(32));
    $verifier = base64url_encode(pack('H*', $random));
    $challenge = base64url_encode(pack('H*', hash('sha256', $verifier)));
    $state = base64url_encode(pack('H*', $state));

    var_dump($verifier, $challenge, $state);
});

Route::get('/redir', function() {
    
});

Route::group(['prefix' => 'market'], function () {
    Route::get('/', function () {
        return Inertia::render('Home');
    })->name('market');

    Route::get('/commodities', function () {
        return Inertia::render('Home');
    })->name('market.commodities');

    Route::get('/rare', function () {
        return Inertia::render('Home');
    })->name('market.rare-commodities');

    Route::get('/traderoutes', function () {
        return Inertia::render('Home');
    })->name('market.traderoutes');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
