<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Services\AchievementService;
use Illuminate\Http\Request;

class AchievementProgressController extends Controller
{
    public function store(
        Request $request,
        AchievementService $achievementService
    ) {
        $validated = $request->validate([
            'event' => ['required', 'string'],
        ]);

        // Comprobar que el evento corresponde a un logro activo.
        $achievementExists = Achievement::where( 'requirement_type',$validated['event'])
            ->where('is_active', true)
            ->exists();

        if (!$achievementExists) {
            return response()->json([
                'success' => false,
                'message' => 'Evento no válido o logro inactivo.',
            ], 422);
        }

        // Registrar el progreso del usuario autenticado.
        $result = $achievementService->recordProgress(
            $request->user(),
            $validated['event']
        );

        return response()->json([
            'success' => true,
            'unlocked' => $result['unlocked'],
            'stats' => $result['stats'],
        ]);
    }
}
