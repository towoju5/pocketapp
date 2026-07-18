<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'amount_type',
        'fixed_amount',
        'min_amount',
        'max_amount',
        'roi_percentage',
        'duration_days',
        'capital_lock',
        'daily_task_limit',
        'max_reinvest_count',
        'fee_discount_percentage',
        'badge_color',
        'wallet_slug',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'capital_lock' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(PlanSubscription::class);
    }
}
