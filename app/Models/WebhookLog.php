<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebhookLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'hash',
        'status',
        'coin',
        'receiver',
        'transferType',
        'metadata',
    ];


    protected $casts = [
        'metadata' => "array"
    ];
    
    public function user(){
        return $this->belongsTo(User::class);
    }
}
