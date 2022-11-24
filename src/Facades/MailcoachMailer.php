<?php

namespace Spatie\MailcoachMailer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Spatie\MailcoachMailer\MailcoachMailer
 */
class MailcoachMailer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Spatie\MailcoachMailer\MailcoachMailer::class;
    }
}
