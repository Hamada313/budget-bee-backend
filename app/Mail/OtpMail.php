<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;
    
    protected string $otp;
    protected string $type;
    /**
     * Create a new message instance.
     */
    public function __construct(string $otp,string $type)
    {
        $this->otp = $otp;
        $this->type = $type;
    }
    
  
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Budget Bee ' . $this->type .' code',    
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.auth.otp',
            with: [
                'otp' => $this->otp,
                'type' => $this->type,  
                'verificationLink' => 'http://localhost:8000',
            ],
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
