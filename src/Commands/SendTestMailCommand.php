<?php

namespace Spatie\MailcoachMailer\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Spatie\MailcoachMailer\Mails\TestMail;

class SendTestMailCommand extends Command
{
    public $signature = 'mailcoach-mailer:send-test --from --to}';

    public $description = 'Send a test mail through Mailcoach';

    public function handle(): int
    {
        $configIsValid = $this->validateConfig();

        if (! $configIsValid) {
            $this->components->error('Errors detected, did not send a mail...');
        }

        $from = $this->option('from') ?? config('mail.from.address');

        if (! $to = $this->option('to')) {
            $to = $this->ask('To which email address should be send a test', config('mail.from.address'));
        }

        $testMail = new TestMail($from, $to);

        Mail::send($testMail);

        $this->components->info("A test mail has been sent, please check if it arrived at {$to}.");

        return self::SUCCESS;
    }

    protected function validateConfig(): bool
    {
        $configProblems = collect([
            'mail.mailers.mailcoach' => "You must set the `mail.mailers.mailcoach.domain` and `mail.mailers.mailcoach.token` config keys",
            'mail.mailers.mailcoach.domain' => "You must set `mail.mailers.mailcoach.domain` config key",
            'mail.mailers.mailcoach.token' => "You must set `mail.mailers.mailcoach.token` config key",
        ])->filter(function(string $message, string $configKey) {
            if (empty(config($configKey))) {
                $this->components->warn($message);
                return true;
            }

            return false;
        });

        return $configProblems->isEmpty();
    }
}
