<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'external_url',
        'reward_amount',
        'wallet_slug',
        'daily_limit',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }
}
