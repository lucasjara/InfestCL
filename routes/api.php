<?php

use App\Http\Controllers\TournamentController;
use App\Http\Controllers\CardController;
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
Route::middleware('throttle:public-api')->get('/questions', function () {
    $maxAge = max(0, (int) config('api.cache_max_age', 60));
    
    return response()->json([
        'response_code' => 0,
        'results' => [
            [
                'type' => 'multiple',
                'difficulty' => 'medium',
                'category' => 'Videojuegos',
                'question' => '¿En qué año fue lanzado originalmente Minecraft?',
                'correct_answer' => '2011',
                'incorrect_answers' => ['2009', '2010', '2013'],
            ],
            [
                'type' => 'multiple',
                'difficulty' => 'easy',
                'category' => 'Videojuegos',
                'question' => '¿Cómo se llama el protagonista principal de The Legend of Zelda?',
                'correct_answer' => 'Link',
                'incorrect_answers' => ['Zelda', 'Ganondorf', 'Epona'],
            ],
            [
                'type' => 'multiple',
                'difficulty' => 'easy',
                'category' => 'Videojuegos',
                'question' => '¿Qué compañía desarrolló la saga Pokémon originalmente?',
                'correct_answer' => 'Game Freak',
                'incorrect_answers' => ['Nintendo', 'Capcom', 'Konami'],
            ],
            [
                'type' => 'multiple',
                'difficulty' => 'hard',
                'category' => 'Videojuegos',
                'question' => '¿Qué jefe aparece al final de la primera partida de Dark Souls antes de acceder al ciclo de Nueva Partida+?',
                'correct_answer' => 'Gwyn, Señor de la Ceniza',
                'incorrect_answers' => ['Ornstein y Smough', 'Nito', 'Seath el Descamado'],
            ],
            [
                'type' => 'multiple',
                'difficulty' => 'medium',
                'category' => 'Videojuegos',
                'question' => '¿Cuál es el nombre del continente donde transcurre The Witcher 3: Wild Hunt?',
                'correct_answer' => 'El Continente',
                'incorrect_answers' => ['Tamriel', 'Azeroth', 'Thedas'],
            ],
            [
                'type' => 'multiple',
                'difficulty' => 'easy',
                'category' => 'Videojuegos',
                'question' => '¿Qué personaje de Nintendo es conocido por rescatar a la princesa Peach?',
                'correct_answer' => 'Mario',
                'incorrect_answers' => ['Luigi', 'Donkey Kong', 'Kirby'],
            ],
            [
                'type' => 'multiple',
                'difficulty' => 'easy',
                'category' => 'Videojuegos',
                'question' => '¿Cuál es el nombre del Pokémon número 25 de la Pokédex Nacional?',
                'correct_answer' => 'Pikachu',
                'incorrect_answers' => ['Raichu', 'Pichu', 'Charmander'],
            ],
            [
                'type' => 'multiple',
                'difficulty' => 'easy',
                'category' => 'Videojuegos',
                'question' => '¿Qué significa la sigla NPC en los videojuegos?',
                'correct_answer' => 'Non-Playable Character',
                'incorrect_answers' => ['New Player Character', 'Network Player Control', 'Next Playable Character'],
            ],
            [
                'type' => 'multiple',
                'difficulty' => 'medium',
                'category' => 'Videojuegos',
                'question' => '¿Qué estudio desarrolló la saga Dark Souls?',
                'correct_answer' => 'FromSoftware',
                'incorrect_answers' => ['Capcom', 'PlatinumGames', 'Kojima Productions'],
            ],
            [
                'type' => 'multiple',
                'difficulty' => 'easy',
                'category' => 'Videojuegos',
                'question' => '¿Cómo se llama el mundo principal de Minecraft?',
                'correct_answer' => 'Overworld',
                'incorrect_answers' => ['Nether', 'The End', 'Aether'],
            ],
            [
                'type' => 'multiple',
                'difficulty' => 'hard',
                'category' => 'Videojuegos',
                'question' => '¿Cuál es el nombre del protagonista de Persona 5?',
                'correct_answer' => 'Ren Amamiya',
                'incorrect_answers' => ['Yu Narukami', 'Makoto Yuki', 'Ryuji Sakamoto'],
            ],
            [
                'type' => 'multiple',
                'difficulty' => 'easy',
                'category' => 'Videojuegos',
                'question' => '¿Qué compañía creó la consola PlayStation?',
                'correct_answer' => 'Sony',
                'incorrect_answers' => ['Microsoft', 'Nintendo', 'Sega'],
            ],
            [
                'type' => 'multiple',
                'difficulty' => 'hard',
                'category' => 'Videojuegos',
                'question' => '¿Cuál es el nombre del reino principal de The Elder Scrolls V: Skyrim?',
                'correct_answer' => 'Skyrim',
                'incorrect_answers' => ['Cyrodiil', 'Morrowind', 'Hammerfell'],
            ],
            [
                'type' => 'multiple',
                'difficulty' => 'medium',
                'category' => 'Videojuegos',
                'question' => '¿Qué personaje protagoniza la saga God of War?',
                'correct_answer' => 'Kratos',
                'incorrect_answers' => ['Atreus', 'Deimos', 'Baldur'],
            ],
            [
                'type' => 'multiple',
                'difficulty' => 'medium',
                'category' => 'Videojuegos',
                'question' => '¿En qué año fue lanzado Grand Theft Auto V?',
                'correct_answer' => '2013',
                'incorrect_answers' => ['2011', '2012', '2015'],
            ],
            [
                'type' => 'multiple',
                'difficulty' => 'hard',
                'category' => 'Videojuegos',
                'question' => '¿Cuál es el nombre de la ciudad principal de Bloodborne?',
                'correct_answer' => 'Yharnam',
                'incorrect_answers' => ['Lordran', 'Boletaria', 'Lothric'],
            ],
            [
                'type' => 'multiple',
                'difficulty' => 'medium',
                'category' => 'Videojuegos',
                'question' => '¿Qué objeto utiliza Sonic para obtener invulnerabilidad temporal en sus juegos?',
                'correct_answer' => 'Monitor de invencibilidad',
                'incorrect_answers' => ['Esmeralda del Caos', 'Anillo dorado', 'Zapato de velocidad'],
            ],
            [
                'type' => 'multiple',
                'difficulty' => 'hard',
                'category' => 'Videojuegos',
                'question' => '¿Quién fue el director del videojuego Metal Gear Solid 2: Sons of Liberty?',
                'correct_answer' => 'Hideo Kojima',
                'incorrect_answers' => ['Shinji Mikami', 'Hideki Kamiya', 'Yoko Taro'],
            ],
            [
                'type' => 'multiple',
                'difficulty' => 'medium',
                'category' => 'Videojuegos',
                'question' => '¿Cuál es el nombre de la región donde transcurren Pokémon Rojo y Azul?',
                'correct_answer' => 'Kanto',
                'incorrect_answers' => ['Johto', 'Hoenn', 'Sinnoh'],
            ],
            [
                'type' => 'multiple',
                'difficulty' => 'hard',
                'category' => 'Videojuegos',
                'question' => '¿Qué videojuego fue el primero de la saga Resident Evil?',
                'correct_answer' => 'Resident Evil (1996)',
                'incorrect_answers' => ['Resident Evil 2', 'Resident Evil 3: Nemesis', 'Resident Evil Code: Veronica'],
            ],
        ],
    ])->withHeaders([
        'Cache-Control' => "public, max-age={$maxAge}, s-maxage={$maxAge}",
        'X-Content-Type-Options' => 'nosniff',
        'X-Frame-Options' => 'DENY',
        'Referrer-Policy' => 'no-referrer',
    ]);

});
Route::post('/cards/upload-image', [CardController::class, 'uploadImage']);