<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\UserStat;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class AchievementService
{
    /**
     * Registrar el progreso de una actividad.
     */
    public function recordProgress(
        User $user,
        string $type,
        int $amount = 1
    ): array {
        if ($amount <= 0) {
            throw new InvalidArgumentException(
                'La cantidad de progreso debe ser mayor que cero.'
            );
        }

        return DB::transaction(function () use ($user, $type, $amount) {

            $unlocked = [];

            $achievements = Achievement::where('is_active', true)
                ->where('requirement_type', $type)
                ->get();

            foreach ($achievements as $achievement) {

                $userAchievement = UserAchievement::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'achievement_id' => $achievement->id,
                    ],
                    [
                        'progress' => 0,
                    ]
                );

                $userAchievement = UserAchievement::whereKey(
                    $userAchievement->id
                )->lockForUpdate()->first();

                // No permitir progresar nuevamente un logro desbloqueado.
                if ($userAchievement->unlocked_at !== null) {
                    continue;
                }

                $progress = min(
                    $userAchievement->progress + $amount,
                    $achievement->requirement_value
                );

                $userAchievement->progress = $progress;

                if ($progress >= $achievement->requirement_value) {

                    $userAchievement->unlocked_at = now();

                    // Crear estadísticas del usuario si aún no existen.
                    UserStat::firstOrCreate(
                        ['user_id' => $user->id],
                        ['total_xp' => 0, 'level' => 1]
                    );

                    $stats = UserStat::where('user_id', $user->id)
                        ->lockForUpdate()
                        ->first();

                    // Entregar experiencia una sola vez.
                    $stats->total_xp += $achievement->xp_reward;
                    $stats->level = $this->calculateLevel(
                        $stats->total_xp
                    );

                    $stats->save();

                    $unlocked[] = [
                        'id' => $achievement->id,
                        'name' => $achievement->name,
                        'xp_reward' => $achievement->xp_reward,
                    ];
                }

                $userAchievement->save();
            }

            return [
                'unlocked' => $unlocked,
                'stats' => UserStat::where('user_id', $user->id)->first(),
            ];
        });
    }

    /**
     * Calcular el nivel según la experiencia acumulada.
     */
    public function calculateLevel(int $xp): int
    {
        return match (true) {
            $xp >= 5000 => 6,
            $xp >= 2000 => 5,
            $xp >= 1000 => 4,
            $xp >= 500 => 3,
            $xp >= 200 => 2,
            default => 1,
        };
    }
}
