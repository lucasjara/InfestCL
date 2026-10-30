<?php

namespace Tests\Feature;

use Tests\TestCase;

class TournamentCreateViewTest extends TestCase
{
    public function test_tournament_create_view_is_rendered(): void
    {
        $response = $this->get(route('tournaments.create'));

        $response->assertStatus(200);
        $response->assertSee('Crear torneo');
        $response->assertSee('Generar torneo');
        $response->assertSee('Participantes');
        $response->assertSee('/api/tournaments', false);
        $response->assertSee('value="1"', false);
        $response->assertSee('value="CLP"', false);
        $response->assertSee('name="prize_description[]"', false);
        $response->assertSee('data-add="prize"', false);
        $response->assertSee('name="prize_quantity[]" type="number" min="1" value="1"', false);
    }
}
