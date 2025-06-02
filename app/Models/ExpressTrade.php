<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpressTrade extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'asset_id',
        'trade_group_id',
        'trade_direction',
        'trade_type',
        'trade_amount',
        'trade_close_time',
        'end_price',
        'trade_wallet',
        'start_price',
        'trade_profit',
        'trade_extra_info',
        'trade_status',
        'trade_copied_count',
        'trade_percentage'
    ];

    protected $casts = [
        'trade_extra_info' => 'array',
    ];
}
