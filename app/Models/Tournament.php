<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tournament extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tournament_title',
        'tournament_participation_fee',
        'tournament_starting_balance',
        'tournament_start_date_time',
        'tournament_rebuy_fee',
        'tournament_reward',
        'tournament_rules',
        'tournament_extra_info',
    ];

    protected $casts = [
        'tournament_extra_info' => 'array',
    ];

    public function subscribers()
    {
        return $this->hasMany(TournamentSubscribers::class, 'tournament_id');
    }

    public function isActive(): bool
    {
        return \Carbon\Carbon::parse($this->tournament_start_date_time)->isFuture();
    }
}
