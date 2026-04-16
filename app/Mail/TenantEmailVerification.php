<?php

namespace App\Mail;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TenantEmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Tenant $tenant,
        public string $verificationUrl
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Vérifiez votre nouvelle adresse email - NexaERP',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.tenant-email-verification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
