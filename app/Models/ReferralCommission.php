<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralCommission extends Model
{
    protected $fillable = [
        'beneficiary_id',
        'referred_user_id',
        'level',
        'activity_type',
        'commissionable_type',
        'commissionable_id',
        'base_amount',
        'percentage',
        'commission_amount',
        'wallet_slug',
    ];

    public function beneficiary()
    {
        return $this->belongsTo(User::class, 'beneficiary_id');
    }

    public function referredUser()
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }

    public function commissionable()
    {
        return $this->morphTo();
    }
}
