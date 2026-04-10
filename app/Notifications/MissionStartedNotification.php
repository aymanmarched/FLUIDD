<?php
namespace App\Notifications;

use App\Models\Mission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MissionStartedNotification extends Notification
{
    use Queueable;

 public function __construct(
        public Mission $mission,
        public int $technicienUserId,
        public string $technicienName
    ) {}
    public function via($notifiable): array
    {
        return ['database'];
    }

      public function toDatabase($notifiable): array
    {
        return [
            'type' => 'mission_started',

            'title' => 'Mission started',
            'message' => "Technicien {$this->technicienName} started mission {$this->mission->reference} (" . strtoupper($this->mission->kind) . ").",

            'reference' => $this->mission->reference,
            'kind' => $this->mission->kind,
            'mission_id' => $this->mission->id,

            // where admin should go when clicking notification
            'url' => route('admin.commandes.missions.show', $this->mission->id),

            'technicien_user_id' => $this->technicienUserId,
            'technicien_name' => $this->technicienName,
        ];
    }
}
