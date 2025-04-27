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
        $created = $this->created_at;
        $close = Carbon::createFromTimeString($this->trade_close_time);
        $diffInSeconds = $close->diffInSeconds($created);

        $hours = floor($diffInSeconds / 3600);
        $minutes = floor(($diffInSeconds % 3600) / 60);
        $seconds = $diffInSeconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
}
