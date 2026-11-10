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
                'code' => 'first_activity',
                'name' => 'Primer paso',
                'description' => 'Completa tu primera actividad.',
                'icon' => 'footprints',
                'category' => 'activities',
                'xp_reward' => 50,
                'requirement_type' => 'activity_completed',
                'requirement_value' => 1,
                'sort_order' => 1,
            ],
            [
                'code' => 'first_card',
                'name' => 'Coleccionista',
                'description' => 'Consigue tu primera carta.',
                'icon' => 'layers',
                'category' => 'collection',
                'xp_reward' => 100,
                'requirement_type' => 'card_collected',
                'requirement_value' => 1,
                'sort_order' => 2,
            ],
            [
                'code' => 'explorer',
                'name' => 'Explorador INFEST',
                'description' => 'Descubre cinco experiencias.',
                'icon' => 'compass',
                'category' => 'exploration',
                'xp_reward' => 150,
                'requirement_type' => 'experience_discovered',
                'requirement_value' => 5,
                'sort_order' => 3,
            ],
            [
                'code' => 'first_tournament',
                'name' => 'Campeón',
                'description' => 'Participa en un torneo.',
                'icon' => 'trophy',
                'category' => 'tournaments',
                'xp_reward' => 250,
                'requirement_type' => 'tournament_joined',
                'requirement_value' => 1,
                'sort_order' => 4,
            ],
            // Logros Torneos
            [
                'code' => 'tournament_john_cena',
                'name' => 'You Can’t See Me',
                'description' => 'Utiliza a John Cena en un torneo.',
                'icon' => 'eye-off',
                'category' => 'tournaments',
                'xp_reward' => 100,
                'requirement_type' => 'tournament_john_cena',
                'requirement_value' => 1,
                'sort_order' => 10,
            ],
            [
                'code' => 'tournament_final_animation',
                'name' => '¡Victoria épica!',
                'description' => 'Llega al final del torneo y visualiza la animación.',
                'icon' => 'trophy',
                'category' => 'tournaments',
                'xp_reward' => 250,
                'requirement_type' => 'tournament_final_animation',
                'requirement_value' => 1,
                'sort_order' => 11,
            ],
            [
                'code' => 'tournament_bracket_viewed',
                'name' => '¡Que comience el torneo!',
                'description' => 'Visualiza un cuadro de torneo generado correctamente.',
                'icon' => 'git-branch',
                'category' => 'tournaments',
                'xp_reward' => 50,
                'requirement_type' => 'tournament_bracket_viewed',
                'requirement_value' => 1,
                'sort_order' => 12,
            ],
        ];
        

        foreach ($achievements as $achievement) {
            Achievement::updateOrCreate(
                ['code' => $achievement['code']],
                $achievement + ['is_active' => true]
            );
        }
    }
}
