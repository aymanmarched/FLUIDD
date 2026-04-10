<?php
namespace App\Notifications;

use App\Models\ConversionProposal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EntretienToRemplacerProposalNotification extends Notification
{
    use Queueable;

    public function __construct(public int $proposalId) {}

    public function via($notifiable): array
    {
        // you already configured SMTP, so mail will work
        return ['database','mail'];
    }

    public function toDatabase($notifiable): array
    {
        $proposal = ConversionProposal::findOrFail($this->proposalId);
 return [
            'type' => 'entretien_to_remplacer',
            'title' => 'Remplacement recommandé',
            'message' => 'Le technicien recommande un remplacement pour votre entretien.',
            'proposal_id' => $proposal->id,
            'token' => $proposal->token,
            'reference' => $proposal->old_reference,
        ];
    }

    public function toMail($notifiable): MailMessage
    {
        $proposal = ConversionProposal::findOrFail($this->proposalId);
        $url = route('client.proposals.show', ['token' => $proposal->token]);

        return (new MailMessage)
            ->subject('Proposition de remplacement')
            ->greeting('Bonjour,')
            ->line("Le technicien recommande de convertir votre commande Entretien ({$proposal->old_reference}) en Remplacer.")
            ->action('Voir la proposition', $url)
            ->line('Merci.');
    }
}
