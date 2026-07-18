<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P2pTrade extends Model
{
    protected $fillable = [
        'offer_id',
        'maker_id',
        'taker_id',
        'seller_id',
        'buyer_id',
        'amount',
        'fiat_amount',
        'wallet_slug',
        'payment_proof_path',
        'status',
        'paid_at',
        'released_at',
        'cancelled_at',
        'dispute_reason',
        'disputed_by',
        'disputed_at',
        'resolved_by',
        'resolution_notes',
        'resolved_at',
        'payment_deadline',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'released_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'disputed_at' => 'datetime',
        'resolved_at' => 'datetime',
        'payment_deadline' => 'datetime',
    ];

    public function offer()
    {
        return $this->belongsTo(P2pOffer::class, 'offer_id');
    }

    public function maker()
    {
        return $this->belongsTo(User::class, 'maker_id');
    }

    public function taker()
    {
        return $this->belongsTo(User::class, 'taker_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function disputedBy()
    {
        return $this->belongsTo(User::class, 'disputed_by');
    }

    public function resolvedBy()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
