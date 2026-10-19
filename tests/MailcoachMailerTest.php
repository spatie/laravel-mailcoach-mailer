<?php

use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Mail;
use Spatie\MailcoachMailer\Exceptions\InvalidMailerConfig;
use Spatie\MailcoachMailer\MailcoachApiTransport;
use Spatie\MailcoachMailer\Tests\TestSupport\Mails\TestMail;

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
});

it('sends mail to the configured api base url or the default domain', function (array $config, string $expectedUrl) {
    config()->set('mail.mailers.mailcoach', [
        ...config('mail.mailers.mailcoach'),
        ...$config,
    ]);

    expectResponse(function (string $method, string $url, array $options) use ($expectedUrl) {
        expect($method)->toBe('POST');
        expect($url)->toBe($expectedUrl);
        expect($options['headers'])->toContain('Authorization: Bearer my-fake-token');

        $body = json_decode($options['body'], true);

        expect($body['to'])->toBe('to@example.com');
        expect($body['subject'])->toBe('Test subject');
        expect($body['html'])->toContain('<a href="https://mailcoach.app/docs');
    });

    expect(Mail::to('to@example.com')->send(new TestMail))->not->toBeNull();
})->with([
    'missing base url' => [[], 'https://fake-domain/api/transactional-mails/send'],
    'null base url' => [['api_base_url' => null], 'https://fake-domain/api/transactional-mails/send'],
    'empty base url' => [['api_base_url' => ''], 'https://fake-domain/api/transactional-mails/send'],
    'internal HTTP with port' => [['api_base_url' => 'http://mailcoach-service:8080'], 'http://mailcoach-service:8080/api/transactional-mails/send'],
    'trailing slash' => [['api_base_url' => 'http://mailcoach-service:8080/'], 'http://mailcoach-service:8080/api/transactional-mails/send'],
    'HTTPS with a path prefix' => [['api_base_url' => 'https://internal.example.com:8443/mailcoach/'], 'https://internal.example.com:8443/mailcoach/api/transactional-mails/send'],
]);

it('will throw an exception when a required config key is not set', function (string $key) {
    config()->set($key, null);

    Mail::mailer('mailcoach');
})->throws(InvalidMailerConfig::class)->with([
    'mail.mailers.mailcoach.domain',
    'mail.mailers.mailcoach.token',
]);
