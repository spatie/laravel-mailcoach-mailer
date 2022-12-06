<?php

use Illuminate\Support\Facades\Mail;
use Spatie\MailcoachMailer\Tests\TestSupport\Mails\TemplateMail;
use Spatie\MailcoachMailer\Tests\TestSupport\Mails\TestMail;

beforeEach(function () {
    config()->set('mail.default', 'mailcoach');

    config()->set('mail.mailers.mailcoach', [
        'transport' => 'mailcoach',
        'domain' => 'test.mailcoach.app',
        'token' => 'fake-token',
    ]);
});

it('can send a regular mailable through the transport', function () {
    expectResponse(function (string $method, string $url, array $options) {
        expect($url)->toBe('https://test.mailcoach.app/api/transactional-mails/send');
        expect($method)->toBe('POST');

        expect($options['headers'][1])->toBe('Authorization: Bearer fake-token');

        $body = json_decode($options['body'], true);

        expect($body['from'])->toBe('from@example.com');
        expect($body['to'])->toBe('to@example.com');
        expect($body['cc'])->toBe('cc@example.com');
        expect($body['bcc'])->toBe('bcc@example.com');
        expect($body['reply_to'])->toBe('replyTo@example.com');

        expect($body['subject'])->toBe('Test subject');
        expect($body['text'])->toContain('test by Mailcoach');
        expect($body['html'])->toContain('<a href="https://mailcoach.app/docs');

        $attachments = $body['attachments'];

        expect($attachments)->toHaveCount(1);
        expect($attachments[0]['name'])->toBe('renamed.txt');
        expect($attachments[0]['content'])->toBe('VGhpcyBpcyBhIHRlc3QgYXR0YWNobWVudC4K');
        expect($attachments[0]['content_type'])->toBe('text/plain');
    });

    Mail::to('to@example.com')->send(new TestMail());
});

it('can send a mail that makes use of a mailcoach mail template', function () {
    expectResponse(function (string $method, string $url, array $options) {
        expect($url)->toBe('https://test.mailcoach.app/api/transactional-mails/send');
        expect($method)->toBe('POST');

        expect($options['headers'][1])->toBe('Authorization: Bearer fake-token');

        $body = json_decode($options['body'], true);
        expect($body['from'])->toBe('from@example.com');
        expect($body['to'])->toBe('to@example.com');

        expect($body['html'])->toBe('use-mailcoach-mail');
        expect($body['mail_name'])->toBe('mail-name');

        expect($body['replacements'])->toBe([
            'replacementName' => 'replacementValue',
            'singleName' => 'singleValue',
            'multipleName' => 'multipleValue',
        ]);
    });

    Mail::to('to@example.com')->send(new TemplateMail());
});
