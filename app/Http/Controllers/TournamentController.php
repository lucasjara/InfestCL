<?php

namespace App\Http\Controllers;

use App\Services\TournamentBracketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function generate(Request $request, TournamentBracketService $brackets): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'game' => ['required', 'string', 'max:255'],
            'format' => ['required', 'in:single_elimination'],
            'max_participants' => ['required', 'integer', 'min:2', 'max:32'],
            'prizes' => ['sometimes', 'array'],
            'prizes.*.place' => ['required_with:prizes', 'integer', 'min:1'],
            'prizes.*.type' => ['required_with:prizes', 'string', 'max:50'],
            'prizes.*.description' => ['nullable', 'string', 'max:255'],
            'prizes.*.amount' => ['nullable', 'numeric', 'min:0'],
            'prizes.*.currency' => ['nullable', 'string', 'max:10'],
            'prizes.*.name' => ['nullable', 'string', 'max:255'],
            'prizes.*.quantity' => ['nullable', 'integer', 'min:0'],
            'settings' => ['sometimes', 'array'],
            'settings.seeding' => ['nullable', 'in:random,seeded'],
            'settings.third_place_match' => ['nullable', 'boolean'],
            'participants' => ['present', 'array', 'min:1', 'max:32'],
            'participants.*.id' => ['required', 'string', 'max:255'],
            'participants.*.name' => ['required', 'string', 'max:255'],
        ]);

        $participants = $validated['participants'];
        $participantIds = array_column($participants, 'id');

        if (count($participantIds) !== count(array_unique($participantIds))) {
            return response()->json([
                'message' => 'Los identificadores de participantes deben ser únicos.',
                'errors' => ['participants.id' => ['Los identificadores de participantes deben ser únicos.']],
            ], 422);
        }

        if (count($participants) > $validated['max_participants']) {
            return response()->json([
                'message' => 'La cantidad de participantes supera max_participants.',
                'errors' => ['participants' => ['La cantidad de participantes supera max_participants.']],
            ], 422);
        }

        return response()->json($brackets->generate($validated));
    }
}
