<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RewardAchiever extends Model
{
    protected $fillable = [
        'member_id',
        'reward_id',
        'achieved_at',
        'member_name_snapshot',
        'member_email_snapshot',
        'member_referral_code_snapshot',
    ];

    protected $casts = [
        'achieved_at' => 'date',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function reward(): BelongsTo
    {
        return $this->belongsTo(Reward::class, 'reward_id');
    }
}
