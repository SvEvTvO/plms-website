<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaxonomyRequestProcessed extends Notification
{
    use Queueable;

    protected $status;
    protected $taxonomyType;
    protected $taxonomyName;
    protected $message;

    public function __construct($status, $taxonomyType, $taxonomyName, $message)
    {
        $this->status = $status; // 'approved' atau 'rejected'
        $this->taxonomyType = $taxonomyType; // 'group', 'category', 'tag'
        $this->taxonomyName = $taxonomyName;
        $this->message = $message;
    }

    // Tentukan jalur pengiriman: via Database
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    // Format data yang akan disimpan ke tabel notifications
    public function toArray(object $notifiable): array
    {
        return [
            'status' => $this->status,
            'type' => $this->taxonomyType,
            'name' => $this->taxonomyName,
            'message' => $this->message,
        ];
    }
}
