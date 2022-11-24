<?php

namespace Spatie\MailcoachMailer\Commands;

use Illuminate\Console\Command;

class MailcoachMailerCommand extends Command
{
    public $signature = 'laravel-mailcoach-mailer';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
