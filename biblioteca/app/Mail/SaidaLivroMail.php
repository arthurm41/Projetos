<?php

namespace App\Mail;

use App\Models\StockWithdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SaidaLivroMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public StockWithdrawal $withdrawal
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'SenaiStock - Saída de livro registrada',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.saida-livro',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}