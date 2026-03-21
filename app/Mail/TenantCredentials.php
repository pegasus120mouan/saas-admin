<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TenantCredentials extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;
    public string $company;
    public string $email;
    public string $password;
    public string $loginUrl;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->company = $data['company'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->loginUrl = $data['login_url'] ?? 'https://espace.nexaerp.pro/login';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenue sur NexaERP - Vos identifiants de connexion',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.tenant-credentials',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
