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
        'trade_currency',
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
    
    protected $with = ['asset'];

    protected $appends = ['trade_duration'];


    protected $casts = [
        'trade_extra_info' => 'array',
    ];

    public function getTradeDurationAttribute()
    {
        if (!$this->trade_close_time) {
            return 0;
        }

        $closeTimestamp = \Carbon\Carbon::parse($this->trade_close_time)->timestamp;
        $nowTimestamp = now()->timestamp;

        $remainingSeconds = $closeTimestamp - $nowTimestamp;

        return $remainingSeconds > 0 ? $remainingSeconds : 0;
    }

    public function asset()
    {
        return $this->belongsTo(Assets::class);
    }
}
