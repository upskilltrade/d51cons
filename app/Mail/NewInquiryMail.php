<?php

namespace App\Mail;

use App\Models\Inquiry;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class NewInquiryMail extends Mailable
{
    public function __construct(public Inquiry $inquiry) {}

    /**
     * The subject carries only the inquiry id so no visitor input reaches a header
     * other than the validated Reply-To address.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->inquiry->email)],
            subject: 'New website inquiry #'.$this->inquiry->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.inquiry',
            text: 'mail.inquiry-text',
        );
    }
}
