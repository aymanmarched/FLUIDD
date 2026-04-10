<?php

namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;









class CommandeChangedNotification extends Notification
{
    use Queueable;

    public function __construct(public array $data) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable): array
    {
        return $this->data;
    }

    public function toArray($notifiable): array
    {
        return $this->data;
    }

    public function toMail($notifiable): MailMessage
    {
        $title     = $this->data['title'] ?? 'Commande mise à jour';
        $client    = $this->data['client_name'] ?? '-';
        $reference = $this->data['reference'] ?? ($this->data['reference_new'] ?? '-');

        // Link based on receiver role
        $role = $notifiable->role ?? null;
        $url  = $this->data['panel_links'][$role]
            ?? $this->data['panel_links']['admin'] ?? null
            ?? $this->data['panel_links']['technicien'] ?? null
            ?? url('/notifications');

        $mail = (new MailMessage)
            ->subject($title . ' - ' . $reference)
            ->line("Client : {$client}")
            ->line("Référence : {$reference}");

        foreach (($this->data['changes'] ?? []) as $c) {
            $field = $c['field'] ?? 'champ';
            $from  = $c['from'] ?? '';
            $to    = $c['to'] ?? '';
            $mail->line("- {$field} : {$from} → {$to}");
        }

        return $mail->action('Ouvrir la commande', $url);
    }
}

