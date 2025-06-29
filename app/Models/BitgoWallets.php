<?php

namespace App\Models;

use Bavix\Wallet\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BitgoWallets extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $hidden = [
        "created_at",
        "updated_at",
        "deleted_at",
        "user_id"
    ];

    protected $casts = [
        'meta_data' => 'array'
    ];

    public function wallet()
    {
        return $this->belongsTo(Bitgo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(TransactionHistory::class);
    }

}
