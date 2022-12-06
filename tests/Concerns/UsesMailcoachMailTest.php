<?php

use Illuminate\Support\Facades\Mail;
use Spatie\MailcoachMailer\Tests\TestSupport\Mails\TestMail;

beforeEach(function() {
    config()->set('mail.default', 'mailcoach');

    config()->set('mail.mailers.mailcoach', [
        'transport' => 'mailcoach',
        'domain' => 'test.mailcoach.app',
        'token' => 'fake-token',
    ]);
});

it('can send a regular mailable through the transport', function() {
    expectResponse(function(string $method, string $url, array $options) {
        expect($url)->toBe('https://test.mailcoach.app/api/transactional-mails/send');
        expect($method)->toBe('POST');

        expect($options['headers'][1])->toBe('Authorization: Bearer fake-token');

        $body = json_decode($options['body'], true);
        expect($body['from'])->toBe('john@example.com');
        expect($body['to'])->toBe('to@example.com');
        expect($body['subject'])->toBe('Test subject');
        expect($body['text'])->toContain('test by Mailcoach');
        expect($body['html'])->toContain('<a href="https://mailcoach.app/docs');
    });

    Mail::to('to@example.com')->send(new TestMail());
});

