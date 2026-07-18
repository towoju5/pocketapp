<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TraderFollow extends Model
{
    protected $fillable = [
        'follower_id',
        'trader_id',
        'copy_stake_amount',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'copy_stake_amount' => 'float',
    ];

    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    public function trader()
    {
        return $this->belongsTo(User::class, 'trader_id');
    }
}
