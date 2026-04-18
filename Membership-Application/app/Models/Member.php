<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Member extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'dob',
        'gender',
        'referral_id',
        'referral_code'
    ];

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'member_id');
    }

    public function referral(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'referral_id');
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(Member::class, 'referral_id');
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Documents::class, 'documentable');
    }
}
