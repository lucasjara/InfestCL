<?php

namespace App\Services;

class TournamentBracketService
{
    public function generate(array $tournament): array
    {
        $participants = $tournament['participants'];
        $settings = $tournament['settings'] ?? [];

        if (($settings['seeding'] ?? 'random') === 'random') {
            shuffle($participants);
        }

        $participantCount = count($participants);
        $bracketSize = $this->nextPowerOfTwo($participantCount);
        $slots = array_pad($participants, $bracketSize, null);
        $roundCount = max(1, (int) log($bracketSize, 2));
        $rounds = [];
        $previousMatches = [];

        for ($roundNumber = 1; $roundNumber <= $roundCount; $roundNumber++) {
            $matchesInRound = $bracketSize / (2 ** $roundNumber);
            $matches = [];

            for ($matchIndex = 0; $matchIndex < $matchesInRound; $matchIndex++) {
                $matchId = "r{$roundNumber}-m".($matchIndex + 1);

                if ($roundNumber === 1) {
                    $participant1 = $slots[$matchIndex * 2] ?? null;
                    $participant2 = $slots[$matchIndex * 2 + 1] ?? null;
                } else {
                    $source1 = $previousMatches[$matchIndex * 2];
                    $source2 = $previousMatches[$matchIndex * 2 + 1];
                    $participant1 = $source1['winner'];
                    $participant2 = $source2['winner'];
                }

                $match = [
                    'id' => $matchId,
                    'round' => $roundNumber,
                    'participant1' => $participant1,
                    'participant2' => $participant2,
                    'winner' => null,
                    'status' => $roundNumber === 1
                        ? $this->firstRoundMatchStatus($participant1, $participant2)
                        : $this->laterRoundMatchStatus($source1, $source2),
                    'next_match_id' => $roundNumber < $roundCount
                        ? 'r'.($roundNumber + 1).'-m'.((int) floor($matchIndex / 2) + 1)
                        : null,
                    'next_slot' => $matchIndex % 2 === 0 ? 1 : 2,
                ];

                if ($match['status'] === 'bye') {
                    $match['winner'] = $participant1 ?? $participant2;
                }

                if ($match['status'] === 'empty') {
                    $match['next_match_id'] = null;
                }

                $matches[] = $match;
            }

            $rounds[] = [
                'round' => $roundNumber,
                'name' => $this->roundName($roundNumber, $roundCount),
                'matches' => $matches,
            ];
            $previousMatches = $matches;
        }

        $result = [
            'name' => $tournament['name'],
            'game' => $tournament['game'],
            'format' => $tournament['format'],
            'max_participants' => $tournament['max_participants'],
            'prizes' => $tournament['prizes'] ?? [],
            'settings' => [
                'seeding' => $settings['seeding'] ?? 'random',
                'third_place_match' => $settings['third_place_match'] ?? false,
            ],
            'participants' => $participants,
            'bracket' => [
                'size' => $bracketSize,
                'round_count' => $roundCount,
                'rounds' => $rounds,
            ],
        ];

        if (($settings['third_place_match'] ?? false) && $bracketSize >= 4) {
            $result['third_place_match'] = [
                'id' => 'third-place',
                'participant1' => null,
                'participant2' => null,
                'winner' => null,
                'status' => 'pending',
            ];
        }

        return $result;
    }

    private function nextPowerOfTwo(int $value): int
    {
        if ($value === 0) {
            return 2;
        }

        $power = 1;
        while ($power < $value && $power < 32) {
            $power *= 2;
        }

        return $power;
    }

    private function firstRoundMatchStatus(?array $participant1, ?array $participant2): string
    {
        if ($participant1 === null && $participant2 === null) {
            return 'empty';
        }

        if ($participant1 === null || $participant2 === null) {
            return 'bye';
        }

        return 'scheduled';
    }

    private function laterRoundMatchStatus(array $source1, array $source2): string
    {
        $participant1 = $source1['winner'];
        $participant2 = $source2['winner'];

        if ($participant1 === null && $participant2 === null) {
            return $source1['status'] === 'empty' && $source2['status'] === 'empty'
                ? 'empty'
                : 'pending';
        }

        if ($participant1 === null || $participant2 === null) {
            if ($source1['status'] === 'empty' || $source2['status'] === 'empty') {
                return 'bye';
            }

            return 'pending';
        }

        return 'scheduled';
    }

    private function roundName(int $roundNumber, int $roundCount): string
    {
        $remainingRounds = $roundCount - $roundNumber + 1;

        return match ($remainingRounds) {
            1 => 'Final',
            2 => 'Semifinals',
            3 => 'Quarterfinals',
            4 => 'Round of 16',
            5 => 'Round of 32',
            default => "Round {$roundNumber}",
        };
    }
}
