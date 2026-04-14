<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
