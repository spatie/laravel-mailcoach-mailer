<?php

namespace Spatie\MailcoachMailer\Tests\TestSupport\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function envelope()
    {
        return new Envelope(
            from: 'from@example.com',
            cc: 'cc@example.com',
            bcc: 'bcc@example.com',
            replyTo: 'replyTo@example.com',
            subject: 'Test subject',
        );
    }

    public function content()
    {
        return new Content(
            markdown: 'mailcoach-mailer::mails.test',
        );
    }

    public function attachments()
    {
        return [
            Attachment::fromPath(__DIR__.'/Attachments/test.txt')->as('renamed.txt'),
        ];
    }
}
