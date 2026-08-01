<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashbackPayout extends Model
{
    protected $fillable = [
        'user_id',
        'cashback_rule_id',
        'type',
        'period_key',
        'promo_code_redemption_id',
        'amount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cashbackRule()
    {
        return $this->belongsTo(CashbackRule::class);
    }
}
