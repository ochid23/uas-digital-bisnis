<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaction;

class EventTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function envelope(): Envelope
    {
        $fromAddress = config('mail.from.address') ?: config('mail.mailers.smtp.username');
        $fromName = config('mail.from.name', 'Admin Naazhi E-Ticket');
        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($fromAddress, $fromName),
            subject: 'E-Ticket Resmi Anda: ' . $this->transaction->event->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            // Menentukan lokasi view HTML email di folder resources/views/emails/...
            view: 'emails.ticket',
        );
    }
}