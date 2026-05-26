<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PendaftaranDisetujui extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $namaCalon,
        public string $username,
        public string $password,
        public string $namaProgram
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: '🎉 Pendaftaranmu Disetujui - Sanggar Goong Prasasti');
    }

    public function content(): Content
{
    return new Content(view: 'emails.pendaftaran.disetujui');
}
}
