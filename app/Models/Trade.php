<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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


    public function getDurationAttribute()
    {
        $created = $this->trade_close_time;
        $closeTime = \Carbon\Carbon::parse($created);
        $now = now();

        $secondsRemaining = $closeTime->diffInSeconds($now, false); 
        
        if ($secondsRemaining > 0) {
            $hours = floor($secondsRemaining / 3600);
            $minutes = floor(($secondsRemaining % 3600) / 60);
            $seconds = $secondsRemaining % 60;
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        } else {
            return  "00:00:00"; 
        }
        
    }
}
