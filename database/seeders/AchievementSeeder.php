<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $achievements = [
            [
                'code' => 'tournament_bracket_viewed',
                'name' => '¡Que comience el torneo!',
                'description' => 'Visualiza tu primer cuadro de torneo. Ahora solo falta que alguien gane.',
                'icon' => 'git-branch',
                'category' => 'tournaments',
                'xp_reward' => 50,
                'requirement_type' => 'tournament_bracket_viewed',
                'requirement_value' => 1,
                'sort_order' => 1,
            ],
            [
                'code' => 'tournament_final_animation',
                'name' => '¡Victoria épica!',
                'description' => 'Llegaste al final del torneo. Al menos aquí sí hay ceremonia de premiación.',
                'icon' => 'trophy',
                'category' => 'tournaments',
                'xp_reward' => 100,
                'requirement_type' => 'tournament_final_animation',
                'requirement_value' => 1,
                'sort_order' => 2,
            ],
            [
                'code' => 'tournament_john_cena',
                'name' => 'No me puedes ver',
                'description' => 'Utiliza a John Cena. Lo difícil no es encontrarlo, es dejar de verlo.',
                'icon' => 'eye-off',
                'category' => 'tournaments',
                'xp_reward' => 150,
                'requirement_type' => 'tournament_john_cena',
                'requirement_value' => 1,
                'sort_order' => 3,
            ],
            [
                'code' => 'first_card_collected',
                'name' => 'Coleccionista de cartas',
                'description' => 'Conseguiste tu primera carta. Todas las grandes colecciones empiezan con una.',
                'icon' => 'cards',
                'category' => 'cards',
                'xp_reward' => 50,
                'requirement_type' => 'first_card_collected',
                'requirement_value' => 1,
                'sort_order' => 4,
            ],
            [
                'code' => 'ten_cards_collected',
                'name' => 'Coleccionista de cartas',
                'description' => 'Colecciona 10 cartas. Dijiste que solo ibas a conseguir una.',
                'icon' => 'cards',
                'category' => 'cards',
                'xp_reward' => 100,
                'requirement_type' => 'ten_cards_collected',
                'requirement_value' => 1,
                'sort_order' => 5,
            ],
            [
                'code' => 'custom_card_created',
                'name' => 'Creador de cartas',
                'description' => 'Crea tu primera carta personalizada.',
                'icon' => 'cards',
                'category' => 'cards',
                'xp_reward' => 100,
                'requirement_type' => 'custom_card_created',
                'requirement_value' => 1,
                'sort_order' => 6,
            ],
            [
                'code' => 'pokemon_collected',
                'name' => 'Pokémon Master',
                'description' => 'Descubriste cómo conseguir Pokémon. Ash estaría orgulloso.',
                'icon' => 'cards',
                'category' => 'cards',
                'xp_reward' => 150,
                'requirement_type' => 'pokemon_collected',
                'requirement_value' => 1,
                'sort_order' => 7,
            ],
            [
                'code' => 'thousand_cards_collected',
                'name' => 'Coleccionista sin remedio',
                'description' => 'Colecciona 1000 cartas. La paciencia es una virtud, el spam de clics también.',
                'icon' => 'cards',
                'category' => 'cards',
                'xp_reward' => 200,
                'requirement_type' => 'thousand_cards_collected',
                'requirement_value' => 1,
                'sort_order' => 8,
            ],
            [
                'code' => 'ultimate_challenge_completed',
                'name' => 'Mucho tiempo libre',
                'description' => 'Completaste todos los desafíos. ¿Todo bien en casa?',
                'icon' => 'star',
                'category' => 'challenges',
                'xp_reward' => 300,
                'requirement_type' => 'ultimate_challenge_completed',
                'requirement_value' => 1,
                'sort_order' => 9,
            ],
            [
                'code' => 'speed_run_completed',
                'name' => 'Speed Runner',
                'description' => 'Menos de 2 minutos. Ni Sonic corre tanto.',
                'icon' => 'zap',
                'category' => 'challenges',
                'xp_reward' => 500,
                'requirement_type' => 'speed_run_completed',
                'requirement_value' => 1,
                'sort_order' => 10,
            ],
            [
                'code' => 'app_downloaded',
                'name' => 'Hombre sabio',
                'description' => 'Descargaste la aplicación móvil. Has elegido sabiamente, joven Padawan.',
                'icon' => 'download',
                'category' => 'app',
                'xp_reward' => 100,
                'requirement_type' => 'app_downloaded',
                'requirement_value' => 1,
                'sort_order' => 11,
            ]
        ];
        

        foreach ($achievements as $achievement) {
            Achievement::updateOrCreate(
                ['code' => $achievement['code']],
                $achievement + ['is_active' => true]
            );
        }
    }
}
