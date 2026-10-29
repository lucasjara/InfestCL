<?php

namespace Tests\Feature;

use Tests\TestCase;

class TournamentApiTest extends TestCase
{
    private function payload(array $overrides = []): array
    {
        return array_replace_recursive([
            'name' => 'INFEST Gaming Cup',
            'game' => 'Tekken 8',
            'format' => 'single_elimination',
            'max_participants' => 8,
            'prizes' => [
                [
                    'place' => 1,
                    'type' => 'money',
                    'description' => 'Primer lugar',
                    'amount' => 100000,
                    'currency' => 'CLP',
                ],
            ],
            'settings' => [
                'seeding' => 'random',
                'third_place_match' => false,
            ],
            'participants' => [
                ['id' => 'p1', 'name' => 'Lucas'],
                ['id' => 'p2', 'name' => 'Matias'],
                ['id' => 'p3', 'name' => 'Camila'],
                ['id' => 'p4', 'name' => 'Diego'],
                ['id' => 'p5', 'name' => 'Valentina'],
                ['id' => 'p6', 'name' => 'Javier'],
                ['id' => 'p7', 'name' => 'Sofia'],
            ],
        ], $overrides);
    }

    public function test_generates_randomized_single_elimination_bracket_with_byes(): void
    {
        $response = $this->postJson('/api/tournaments', $this->payload());

        $response->assertOk()
            ->assertJsonPath('name', 'INFEST Gaming Cup')
            ->assertJsonPath('format', 'single_elimination')
            ->assertJsonPath('bracket.size', 8)
            ->assertJsonPath('bracket.round_count', 3);

        $firstRound = $response->json('bracket.rounds.0.matches');
        $this->assertCount(4, $firstRound);
        $this->assertGreaterThanOrEqual(1, count(array_filter(
            $firstRound,
            fn (array $match) => $match['status'] === 'bye'
        )));
        $this->assertCount(7, $response->json('participants'));
    }

    public function test_rejects_more_participants_than_max_participants(): void
    {
        $payload = $this->payload([
            'max_participants' => 2,
        ]);

        $this->postJson('/api/tournaments', $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('participants');
    }

    public function test_rejects_duplicate_participant_ids(): void
    {
        $payload = $this->payload();
        $payload['participants'][1]['id'] = 'p1';

        $this->postJson('/api/tournaments', $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('participants.id');
    }

    public function test_rejects_participants_above_hard_limit_of_32(): void
    {
        $participants = [];
        for ($index = 1; $index <= 33; $index++) {
            $participants[] = ['id' => "p{$index}", 'name' => "Player {$index}"];
        }

        $this->postJson('/api/tournaments', $this->payload([
            'max_participants' => 32,
            'participants' => $participants,
        ]))->assertUnprocessable();
    }

    public function test_includes_third_place_match_when_enabled(): void
    {
        $payload = $this->payload([
            'settings' => [
                'seeding' => 'random',
                'third_place_match' => true,
            ],
        ]);

        $this->postJson('/api/tournaments', $payload)
            ->assertOk()
            ->assertJsonPath('third_place_match.id', 'third-place')
            ->assertJsonPath('third_place_match.status', 'pending');
    }

    public function test_does_not_include_third_place_match_when_disabled(): void
    {
        $this->postJson('/api/tournaments', $this->payload())
            ->assertOk()
            ->assertJsonMissingPath('third_place_match');
    }
}
