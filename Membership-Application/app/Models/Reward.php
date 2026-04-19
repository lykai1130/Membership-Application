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

    public static function rewardValueForReferralCount(int $referralCount): ?int
    {
        if ($referralCount === 10) {
            return 100;
        }

        if ($referralCount === 50) {
            return 500;
        }

        if ($referralCount === 100) {
            return 1000;
        }

        if ($referralCount > 100 && $referralCount % 10 === 0) {
            return 150;
        }

        return null;
    }

    public static function resolveForPromotion(int $promotionId, int $referralCount): ?self
    {
        $rewardValue = self::rewardValueForReferralCount($referralCount);
        if ($rewardValue === null) {
            return null;
        }

        return self::firstOrCreate(
            [
                'promotion_id' => $promotionId,
                'referral_count' => $referralCount,
            ],
            [
                'reward_value' => $rewardValue,
            ]
        );
    }
}
