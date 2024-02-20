<?php

namespace Spatie\MailcoachMailer\Tests\TestSupport\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Spatie\MailcoachMailer\Concerns\UsesMailcoachMail;

class TemplateMail extends Mailable
{
    use Queueable, SerializesModels, UsesMailcoachMail;

    public function envelope()
    {
        return new Envelope(
            from: 'from@example.com',
            subject: 'Template Mail',
        );
    }

    public function build()
    {
        $this
            ->mailcoachMail('mail-name', [
                'replacementName' => 'replacementValue',
            ], 'transactional-mailer')
            ->replacing('singleName', 'singleValue')
            ->replacing(['multipleName' => 'multipleValue']);
    }
}
