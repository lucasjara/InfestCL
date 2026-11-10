<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\UserAchievement;
use App\Models\UserStat;
use App\Services\AchievementService;
use Illuminate\Http\Request;

class AchievementProgressController extends Controller
{
    /**
     * Mostrar la pantalla de trofeos del usuario.
     */
    public function index()
    {
        $user = auth()->user();

        // Estadísticas del usuario.
        $stats = UserStat::firstOrNew(
            ['user_id' => $user->id],
            ['total_xp' => 0, 'level' => 1]
        );

        // Obtener el progreso individual de los logros.
        $userProgress = UserAchievement::where('user_id', $user->id)
            ->get()
            ->keyBy('achievement_id');

        // Obtener catálogo de logros activos.
        $achievements = Achievement::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Incorporar progreso y estado de desbloqueo.
        $achievements->each(function ($achievement) use ($userProgress) {

            $progress = $userProgress->get($achievement->id);

            $achievement->setAttribute(
                'is_unlocked',
                $progress !== null && $progress->unlocked_at !== null
            );

            $achievement->setAttribute(
                'progress',
                $progress?->progress ?? 0
            );
        });

        // Calcular progreso hacia el siguiente nivel.
        $totalXp = $stats->total_xp;
        $level = $stats->level;

        $levelStart = match ($level) {
            2 => 200,
            3 => 500,
            4 => 1000,
            5 => 2000,
            6 => 5000,
            default => 0,
        };

        $nextLevelXp = match ($level) {
            1 => 200,
            2 => 500,
            3 => 1000,
            4 => 2000,
            5 => 5000,
            default => null,
        };

        $levelProgress = $nextLevelXp
            ? min(100, max(0, (
                ($totalXp - $levelStart) /
                ($nextLevelXp - $levelStart)
            ) * 100))
            : 100;

        return view('achievements.index', compact(
            'stats',
            'achievements',
            'levelProgress'
        ));
    }

    /**
     * Registrar el progreso de una actividad.
     */
    public function store(
        Request $request,
        AchievementService $achievementService
    ) {
        $validated = $request->validate([
            'event' => ['required', 'string'],
        ]);

        // Comprobar que el evento corresponde a un logro activo.
        $achievementExists = Achievement::where(
            'requirement_type',
            $validated['event']
        )
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