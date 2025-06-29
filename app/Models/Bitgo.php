<?php

namespace App\Models;

use Bavix\Wallet\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bitgo extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'wallet_id', 'wallet_name', 'wallet_ticker',
        'type', 'require_memo', 'can_deposit',
        'can_payout', 'coin_logo', 'meta_data',
    ];

    protected $casts = [
        'require_memo' => 'boolean',
        'can_deposit' => 'boolean',
        'can_payout' => 'boolean',
        'meta_data' => 'array',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'wallet_id', 'wallet_id');
    }
}
