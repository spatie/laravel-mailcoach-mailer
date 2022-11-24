<?php

namespace Spatie\MailcoachMailer\Mails;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class TestMail extends Mailable
{
    public function __construct(
        public $from,
        public $to
    )
    {
    }

    public function envelope()
    {
        return new Envelope(
            from: $this->from,
            to: $this->to,
            subject: "This is a test mail from Mailcoach",
        );
    }

    public function content()
    {
        return new Content(
            markdown: 'mail.test',
        );
    }
}
