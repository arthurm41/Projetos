<?php

namespace App\Mail;

use App\Models\BookRequisition;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NovaRequisicaoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public BookRequisition $requisition
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'SenaiStock - Nova requisição de livro pendente',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.nova-requisicao',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
