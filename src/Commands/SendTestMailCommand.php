<?php

namespace Spatie\MailcoachMailer\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Spatie\MailcoachMailer\Mails\TestMail;

class SendTestMailCommand extends Command
{
    public $signature = 'mailcoach-mailer:send-test {--from=} {--to=} {--mailer=}';

    public $description = 'Send a test mail through Mailcoach';

    public function handle(): int
    {
        $from = $this->option('from');

        if (! $from) {
            $from = config('mail.from.address');
        }

        if (! $to = $this->option('to')) {
            $to = $this->ask('To which email address should we send a test?', config('mail.from.address'));
        }

        if (! $mailerName = $this->option('mailer')) {
            $mailerName = (string) config('mail.default');
        }

        if (! $this->isValidMailer($mailerName)) {
            return self::FAILURE;
        }

        $testMail = new TestMail($from, $to);

        $this->warn('Sending test email...');

        Mail::mailer($mailerName)->send($testMail);

        $this->components->info("A test mail has been sent, please check if it arrived at {$to}.");

        return self::SUCCESS;
    }

    protected function isValidMailer(string $mailerName): bool
    {
        if (empty($mailerName)) {
            $this->components->error('You did not specify a mailer name. Make should specify a default one in the `mail.default` config value');

            return false;
        }

        if (config("mail.mailers.{$mailerName}") === null) {
            $this->components->error("Your mailer named `$mailerName` does not have any configuration. Make sure you add a mailer configuration in the `mail.mailers.{$mailerName}` config value");

            return false;
        }

        if (config("mail.mailers.{$mailerName}.transport") !== 'mailcoach') {
            $this->components->error("Your mailer named `$mailerName` does not seem to be a mailcoach mailer. Make sure you set the  `mail.mailers.{$mailerName}.transport` config value to 'mailcoach'");

            return false;
        }

        return true;
    }
}
