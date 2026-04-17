<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promotion extends Model
{
    protected $table = 'promotion';

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'status',
    ];

    public function rewards(): HasMany
    {
        return $this->hasMany(Reward::class, 'promotion_id');
    }
}
