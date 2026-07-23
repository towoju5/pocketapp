<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class P2pOffer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'type',
        'wallet_slug',
        'currency',
        'sell_currency',
        'buy_currency',
        'price_per_unit',
        'min_limit',
        'max_limit',
        'available_amount',
        'payment_methods',
        'terms',
        'status',
    ];

    protected $casts = [
        'payment_methods' => 'array',
    ];

    public function maker()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function trades()
    {
        return $this->hasMany(P2pTrade::class, 'offer_id');
    }
}
