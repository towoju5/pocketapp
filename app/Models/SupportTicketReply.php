<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicketReply extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'is_admin_reply',
        'message',
        'attachment_path',
    ];

    protected $casts = [
        'is_admin_reply' => 'boolean',
    ];

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
