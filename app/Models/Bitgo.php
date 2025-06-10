<?php

namespace App\Models;

use Bavix\Wallet\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bitgo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['wallet_id', 'wallet_name', 'type', 'balance'];

    protected $casts = [
        "meta_data" => "array"
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'wallet_id', 'wallet_id');
    }
}
