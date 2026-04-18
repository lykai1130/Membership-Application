<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Address extends Model
{
    protected $table = 'address';

    protected $fillable = [
        'member_id',
        'address_type_id',
        'line1',
        'line2',
        'city',
        'state',
        'postal_code',
        'country'
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function addressType(): BelongsTo
    {
        return $this->belongsTo(AddressType::class, 'address_type_id');
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Documents::class, 'documentable');
    }
}
