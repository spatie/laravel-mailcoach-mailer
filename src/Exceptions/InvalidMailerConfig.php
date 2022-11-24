<?php

namespace Spatie\MailcoachMailer\Exceptions;

use Exception;

class InvalidMailerConfig extends Exception
{
    public static function missingKey(string $key)
    {
        return new self("Your Mailcoach mailer config does not contain the required key `$key`. Make sure you add it in the Mailcoach mailer you configured in config/mail.php. You'll find more info in our docs at https://mailcoach.app/docs");
    }

    public static function missingValue(string $key)
    {
        return new self("Your Mailcoach mailer config does not specify a value for `$key`. Make sure you add to add a value in config/mail.php. You'll find more info in our docs at https://mailcoach.app/docs");
    }
}
