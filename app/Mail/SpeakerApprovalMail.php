<?php

namespace App\Mail;

use App\Models\Speaker;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SpeakerApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public Speaker $speaker;
    public string $feedbackUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Speaker $speaker, string $feedbackUrl)
    {
        $this->speaker = $speaker;
        $this->feedbackUrl = $feedbackUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Event Approved - Feedback Requested',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.speaker-approval',
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
    {
        return [];
    }
}
