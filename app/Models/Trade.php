<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Trade extends Model
{
    protected $guarded = [];

    protected $casts = [
        "trade_extra_info" => "array",
    ];


    public function getDurationAttribute()
    {
        $created = $this->trade_close_time;
        $closeTime = \Carbon\Carbon::parse($created);
        $now = now(); // Current time

        $secondsRemaining = $closeTime->diffInSeconds($now, false); // false to get negative if expired

        if ($secondsRemaining > 0) {
            $hours = floor($secondsRemaining / 3600);
            $minutes = floor(($secondsRemaining % 3600) / 60);
            $seconds = $secondsRemaining % 60;
            \Log::debug([
                'hours' => $hours, 
                'minutes' => $minutes, 
                'seconds' => $seconds,
                'secondsRemaining' => $secondsRemaining
            ]);
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        } else {
            return  "00:00:00"; // Time expired
        }
        
    }
}
