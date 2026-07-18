<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralCommissionRate extends Model
{
    protected $fillable = [
        'level',
        'activity_type',
        'percentage',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
