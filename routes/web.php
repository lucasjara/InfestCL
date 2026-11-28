
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\AchievementProgressController;

/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/

Route::middleware('throttle:120,1')->group(function () {

    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::view('/tournaments/create', 'tournaments.create')
        ->name('tournaments.create');

    Route::view('/tournaments/bracket', 'tournaments.bracket')
        ->name('tournaments.bracket');

    Route::view('/cards/list', 'cards.list')
        ->name('cards.list');

    Route::get('/witcher3/random', function () {
        $characters = require resource_path('data/witcher3.php');
        return response()->json($characters[array_rand($characters)]);
    })->name('witcher3.random');

    Route::view('/album', 'album.index')
        ->name('album.index');

    Route::view('/album/{id}', 'album.show')
        ->name('album.show');

    Route::view('/experiencias', 'experiences.index')
        ->name('experiences.index');

    Route::view('/roulette', 'roulette.index')
        ->name('roulette.index');

    Route::view('/apps', 'app.index')
        ->name('app.index');

    Route::view('/politica-de-privacidad', 'pages.privacy-policy')
        ->name('privacy-policy');
});

/*
|--------------------------------------------------------------------------
| Descarga de INFESTCard
|--------------------------------------------------------------------------
*/

Route::prefix('app')->name('app.')->group(function () {

    Route::view('/', 'app-download')
        ->name('download');

    Route::get('/apk', function () {

        $path = storage_path('app/private/downloads/cl.infest.infestcard.apk');

        abort_unless(is_file($path), 404, 'APK no encontrado');

        return response()->download(
            $path,
            'cl.infest.infestcard.apk',
            [
                'Content-Type' => 'application/vnd.android.package-archive',
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'private, no-store',
            ]
        );

    })->middleware('throttle:20,1')->name('apk');
});

/*
|--------------------------------------------------------------------------
| Autenticación Google OAuth
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return redirect()->route('auth.google');
})->middleware('throttle:10,1')->name('login');

Route::get('/auth/google/redirect', [
    GoogleAuthController::class,
    'redirect'
])->middleware('throttle:10,1')->name('auth.google');

Route::get('/auth/google/callback', [
    GoogleAuthController::class,
    'callback'
])->middleware('throttle:20,1')->name('auth.google.callback');

/*
|--------------------------------------------------------------------------
| Rutas protegidas
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/trofeos', [
        AchievementProgressController::class,
        'index'
    ])->middleware('throttle:60,1')
      ->name('achievements.index');

    Route::post('/logout', [
        GoogleAuthController::class,
        'logout'
    ])->middleware('throttle:10,1')
      ->name('logout');

    Route::post('/achievements/events', [
        AchievementProgressController::class,
        'store'
    ])->middleware('throttle:30,1')
      ->name('achievements.events.store');
});
