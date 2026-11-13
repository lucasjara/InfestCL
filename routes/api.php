<?php

use App\Http\Controllers\TournamentController;
use App\Http\Controllers\CardController;
use App\Http\Middleware\PublicApiHeaders;
use Illuminate\Support\Facades\Route;

// Public API
Route::middleware([
    PublicApiHeaders::class,
    'throttle:public-api',
])->group(function () {

    Route::get('/events', function () {
        return response()->json(
            require resource_path('data/events.php')
        );
    });

    Route::get('/questions', function () {
        $questions = require resource_path('data/questions.php');
        shuffle($questions);

        $selected = array_slice($questions, 0, 10);

        return response()->json([
            'response_code' => 0,
            'results' => $selected,
        ]);
    });

    Route::post('/tournaments', [
        TournamentController::class,
        'generate',
    ]);

});

// Image uploads
Route::middleware([
    PublicApiHeaders::class,
    'throttle:uploads',
])->group(function () {

    Route::post('/cards/upload-image', [
        CardController::class,
        'uploadImage',
    ]);

});
