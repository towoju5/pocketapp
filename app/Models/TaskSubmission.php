<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskSubmission extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'proof_url',
        'status',
        'submitted_date',
        'reward_amount',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
        'credited_at',
    ];

    protected $casts = [
        'submitted_date' => 'date',
        'reviewed_at' => 'datetime',
        'credited_at' => 'datetime',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
