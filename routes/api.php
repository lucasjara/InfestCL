<?php

use App\Http\Controllers\TournamentController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:public-api')->post('/tournaments', [TournamentController::class, 'generate']);

Route::middleware('throttle:public-api')->get('/events', function () {
    $maxAge = max(0, (int) config('api.cache_max_age', 60));

    return response()->json([
        [
            'id' => '01',
            'name' => 'INFEST Chile',
            'description' => 'INFEST Chile es una marca dedicada a la organización, producción y promoción de eventos, creando experiencias que conectan a las personas a través del entretenimiento, la tecnología y la cultura. Con raíces en los encuentros gamer y tarreos realizados en Temuco, INFEST ha evolucionado para ampliar su propuesta y reunir a distintas comunidades en torno a experiencias presenciales y digitales.',
            'type' => 'gaming',
            'brand' => 'INFEST',
            'status' => 'completed',
            'date' => '2026-01-01',
            'location' => [
                'name' => 'INACAP Temuco',
                'city' => 'Temuco',
                'region' => 'Araucanía',
                'country' => 'Chile',
            ],
            'tags' => [
                'gaming',
                'cosplay',
                'anime',
            ],
            'featured' => false,
            'visibility' => 'public',
        ],
        [
            'id' => '02',
            'name' => 'INFEST Night Party',
            'description' => 'INFEST Night Party es la línea de INFEST Chile enfocada en la vida nocturna, la música y el entretenimiento. A través de sus fiestas y eventos, busca crear espacios de encuentro social donde las personas puedan compartir, disfrutar y vivir experiencias memorables, manteniendo la identidad de INFEST en un ambiente orientado a la diversión y la conexión entre personas.',
            'type' => 'nightlife',
            'brand' => 'INFEST Night Party',
            'status' => 'completed',
            'date' => '2026-01-02',
            'location' => [
                'name' => 'FestHaus Temuco',
                'city' => 'Temuco',
                'region' => 'Araucanía',
                'country' => 'Chile',
            ],
            'tags' => [
                'nightlife',
            ],
            'featured' => false,
            'visibility' => 'public',
        ],
    ])->withHeaders([
        'Cache-Control' => "public, max-age={$maxAge}, s-maxage={$maxAge}",
        'X-Content-Type-Options' => 'nosniff',
        'X-Frame-Options' => 'DENY',
        'Referrer-Policy' => 'no-referrer',
    ]);
});
