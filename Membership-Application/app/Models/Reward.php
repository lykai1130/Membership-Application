<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reward extends Model
{
    protected $table = 'reward';

    protected $fillable = [
        'promotion_id',
        'referral_count',
        'reward_value',
    ];

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class, 'promotion_id');
    }

    public function rewardAchievers(): HasMany
    {
        return $this->hasMany(RewardAchiever::class, 'reward_id');
    }
}
