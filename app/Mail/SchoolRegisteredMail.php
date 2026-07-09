<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SchoolRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $operatorName;
    public $sekolahName;
    public $npsn;
    public $kodeSekolah;
    public $jenjang;
    public $email;
    public $passwordRaw;
    public $loginUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(
        $operatorName,
        $sekolahName,
        $npsn,
        $kodeSekolah,
        $jenjang,
        $email,
        $passwordRaw,
        $loginUrl
    ) {
        $this->operatorName = $operatorName;
        $this->sekolahName = $sekolahName;
        $this->npsn = $npsn;
        $this->kodeSekolah = $kodeSekolah;
        $this->jenjang = $jenjang;
        $this->email = $email;
        $this->passwordRaw = $passwordRaw;
        $this->loginUrl = $loginUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pendaftaran Berhasil - Akun Sekolah',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.school_registered',
        );
    }

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
