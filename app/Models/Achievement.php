<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Achievement extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'icon',
        'category',
        'xp_reward',
        'requirement_type',
        'requirement_value',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'xp_reward' => 'integer',
            'requirement_value' => 'integer',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function userAchievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }
}