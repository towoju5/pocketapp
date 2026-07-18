<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'stake_amount',
        'roi_percentage',
        'expected_payout',
        'wallet_slug',
        'starts_at',
        'matures_at',
        'status',
        'reinvest_count',
        'parent_subscription_id',
        'payout_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'matures_at' => 'datetime',
        'payout_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function parent()
    {
        return $this->belongsTo(PlanSubscription::class, 'parent_subscription_id');
    }
}
