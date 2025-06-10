<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    protected $guarded = [];
    // protected $fillable = [
    //     'trade_currency',
    //     'trade_direction',
    //     'trade_amount',
    //     'trade_close_time',
    //     'trade_extra_info',
    //     'start_price',
    //     'trade_status',
    //     'trade_copied_count',
    //     'user_id',
    //     'trade_wallet',
    //     'trade_profit',
    //     'trade_percentage'
    // ];

    protected $casts = [
        "trade_extra_info" => "array",
    ];

    protected $appends = ['trade_duration'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function getTradeDurationAttribute()
    // {
    //     $created   = $this->trade_close_time;
    //     $closeTime = \Carbon\Carbon::parse($created);
    //     $now       = now();

    //     $secondsRemaining = $closeTime->diffInSeconds($now, false);

    //     if ($secondsRemaining > 0) {
    //         return $secondsRemaining;
    //         // $hours = floor($secondsRemaining / 3600);
    //         // $minutes = floor(($secondsRemaining % 3600) / 60);
    //         // $seconds = $secondsRemaining % 60;
    //         // return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    //     } else {
    //         return 0;
    //     }
    // }

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
}
