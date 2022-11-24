<?php

use Illuminate\Support\Facades\Mail;
use Spatie\MailcoachMailer\Commands\SendTestMailCommand;
use function Pest\Laravel\artisan;

beforeEach(function() {
    Mail::fake();
});

it('can send a test mail', function () {
    config()->set('mail.default', 'mailcoach');

    config()->set('mail.mailers.mailcoach', [
        'transport' => 'mailcoach',
    ]);

    artisan(SendTestMailCommand::class, ['--to' => 'john@example.com'])
        ->assertSuccessful();
});

it('it will complain that the default mailer is not a mailcoach one', function () {
    artisan(SendTestMailCommand::class, ['--to' => 'john@example.com'])
        ->expectsOutputToContain('does not seem to be a mailcoach mailer')
        ->assertFailed();
});
