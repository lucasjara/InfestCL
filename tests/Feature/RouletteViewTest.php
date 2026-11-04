<?php

namespace Tests\Feature;

use Tests\TestCase;

class RouletteViewTest extends TestCase
{
    public function test_roulette_view_renders_direct_games_and_final_rewards(): void
    {
        $response = $this->get(route('roulette.index'));

        $response->assertStatus(200);
        $response->assertSee('Elegir directamente');
        $response->assertSee('data-direct-challenge="rock-paper-scissors"', false);
        $response->assertSee('data-direct-challenge="space-defender"', false);
        $response->assertSee('data-direct-challenge="trivia"', false);
        $response->assertSee('data-direct-challenge="maze"', false);
        $response->assertSee('INFEST MASTER');
        $response->assertSee('INFEST Speed Master');
        $response->assertSee('data-celebration-badges', false);
        $response->assertSee('data-roulette-final-time', false);
        $response->assertSee('data-direct-selector hidden', false);
        $response->assertSee('data-infest-master-badge data-unlocked="false" hidden', false);
        $response->assertSee('data-speed-master-badge data-unlocked="false" hidden', false);
    }
}
