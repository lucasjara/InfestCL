<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleAuthController;


Route::get('/', function () {
    return view('welcome');
});

Route::view('/tournaments/create', 'tournaments.create')->name('tournaments.create');
Route::view('/tournaments/bracket', 'tournaments.bracket')->name('tournaments.bracket');
Route::view('/cards/list', 'cards.list')->name('cards.list');
Route::view('/album', 'album.index')->name('album.index');
Route::view('/album/{id}', 'album.show')->name('album.show');
Route::view('/experiencias', 'experiences.index')->name('experiences.index');
Route::view('/roulette', 'roulette.index')->name('roulette.index');
Route::view('/apps', 'app.index')->name('app.index');

Route::prefix('app')->name('app.')->group(function () {
    Route::view('/', 'app-download')->name('download');

    Route::get('/apk', function () {
        $path = public_path('downloads/cl.infest.infestcard.apk');
        abort_unless(is_file($path), 404, 'APK no encontrado');
        return response()->download(
            $path,
            'cl.infest.infestcard.apk',
            [
                'Content-Type' => 'application/vnd.android.package-archive',
            ]
        );
    })->name('apk');
});
Route::view('/politica-de-privacidad', 'pages.privacy-policy')->name('privacy-policy');
// Acceso público
Route::get('/login', function () {
    return redirect()->route('auth.google');
})->name('login');

// Autenticación Google
Route::get('/auth/google/redirect', [
    GoogleAuthController::class,
    'redirect'
])->name('auth.google');

Route::get('/auth/google/callback', [
    GoogleAuthController::class,
    'callback'
])->name('auth.google.callback');

Route::post('/logout', [
    GoogleAuthController::class,
    'logout'
])->middleware('auth')->name('logout');

// Trofeos: acceso exclusivo para usuarios autenticados
Route::middleware('auth')->group(function () {
    Route::view('/trofeos', 'achievements.index')
        ->name('achievements.index');
});
// Registro de eventos de logros: acceso exclusivo para usuarios autenticados
Route::middleware('auth')->post(
    '/achievements/events',
    [AchievementProgressController::class, 'store']
)->name('achievements.events.store');