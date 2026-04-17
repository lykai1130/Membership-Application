<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Documents extends Model
{
    protected $fillable = [
        'documentable_id',
        'documentable_type',
        'document_name',
        'document_path',
    ];

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }
}
