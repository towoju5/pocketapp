<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TournamentSubscribers extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'tournament_id',
        'tournament_subscription_fee',
        'tournament_subscription_date_time',
        'tournament_subscription_status',
        'tournament_wining_status',
        'tournament_subscription_extra_info',
    ];

    protected $casts = [
        'tournament_subscription_extra_info' => 'array',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class, 'tournament_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
