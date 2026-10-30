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
        $response->assertSee('<option value="money">Dinero</option>', false);
        $response->assertSee('<option value="item">Objeto</option>', false);
        $response->assertSee('data-thousands', false);
        $response->assertSee("window.open('', '_blank')", false);
    }

    public function test_tournament_bracket_view_is_rendered(): void
    {
        $response = $this->get(route('tournaments.bracket'));

        $response->assertStatus(200);
        $response->assertSee('Cuadro generado');
        $response->assertSee('infest-tournament', false);
        $response->assertSee('Nuevo torneo');
        $response->assertSee('data-winner-action', false);
        $response->assertSee('Agregar como ganador', false);
        $response->assertSee('Eliminar selección de ganador', false);
        $response->assertSee('Ganador:', false);
        $response->assertSee('function getChampion()', false);
        $response->assertSee('Segundo lugar:', false);
        $response->assertSee('Tercer lugar:', false);
        $response->assertSee('Resolver tercer lugar', false);
        $response->assertSee('data-third-place-action', false);
        $response->assertSee('easter-egg-card', false);
        $response->assertSee('img/easter_eggs/jhon_cena.jpg', false);
    }
}
