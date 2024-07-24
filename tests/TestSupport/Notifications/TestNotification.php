<?php

namespace Spatie\MailcoachMailer\Tests\TestSupport\Notifications;

use Illuminate\Notifications\Notification;
use Spatie\MailcoachMailer\Notifications\MailcoachMessage;

class TestNotification extends Notification
{
    public function via() : array
    {
        return ['mail'];
    }

    public function toMail()
    {
        return (new MailcoachMessage())
            ->usingMail('mail-name')
            ->usingMailer('transactional-mailer')
            ->replacing('singleName', 'singleValue')
            ->replacing(['multipleName' => 'multipleValue'])
            ->from('from@example.com');
    }
}
