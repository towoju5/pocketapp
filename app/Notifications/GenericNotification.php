<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class GenericNotification extends Notification
{
    public function __construct(
        protected string $title,
        protected string $body,
        protected ?string $url = null,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'url' => $this->url,
        ];
    }
}
