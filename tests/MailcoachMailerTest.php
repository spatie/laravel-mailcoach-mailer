<?php

use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Mail;
use Spatie\MailcoachMailer\Exceptions\InvalidMailerConfig;
use Spatie\MailcoachMailer\MailcoachApiTransport;

beforeEach(function () {
    config()->set('mail.default', 'mailcoach');

    config()->set('mail.mailers.mailcoach', [
        'transport' => 'mailcoach',
        'domain' => 'fake-domain',
        'token' => 'my-fake-token',
    ]);
});

it('can register a transport', function () {
    $mailer = Mail::mailer('mailcoach');

    expect($mailer)->toBeInstanceOf(Mailer::class);

    /** @var MailcoachApiTransport $transport */
    $transport = $mailer->getSymfonyTransport();

    expect($transport)->toBeInstanceOf(MailcoachApiTransport::class);

    expect(invade($transport))->apiToken->toBe('my-fake-token');
    expect(invade($transport))->apiBaseUrl->toBeNull();
});

it('can configure the api base url', function () {
    config()->set('mail.mailers.mailcoach.api_base_url', 'http://mailcoach-service:8080/');

    $transport = Mail::mailer('mailcoach')->getSymfonyTransport();

    expect(invade($transport))->apiBaseUrl->toBe('http://mailcoach-service:8080');
});

it('will throw an exception when a required config key is not set', function (string $key) {
    config()->set($key, null);

    Mail::mailer('mailcoach');
})->throws(InvalidMailerConfig::class)->with([
    'mail.mailers.mailcoach.domain',
    'mail.mailers.mailcoach.token',
]);
