<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TechnicianCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

      public string $name;
    public string $email;
    public string $password;
    public string $loginUrl;

    public function __construct(string $name, string $email, string $password, string $loginUrl)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->loginUrl = $loginUrl;
    }

    public function build()
    {
        return $this->subject('Accès Technicien - Vos identifiants')
            ->view('emails.technician_credentials');
    }
   

    /**
     * Get the message envelope.
     */
   

    /**
     * Get the message content definition.
     */
   

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
