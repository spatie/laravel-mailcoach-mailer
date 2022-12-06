<?php

use Spatie\MailcoachMailer\MailcoachMailerServiceProvider;
use Spatie\MailcoachMailer\Tests\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

uses(TestCase::class)->in(__DIR__);

function expectResponse(callable $expectations): void
{
    $client = new MockHttpClient(function (string $method, string $url, array $options) use ($expectations): ResponseInterface {
        $expectations($method, $url, $options);

        return new MockResponse(json_encode(['MessageID' => 'foobar']), [
            'http_code' => 200,
        ]);
    });

    registerClient($client);
}

function registerClient(HttpClientInterface $client): void
{
    $provider = new MailcoachMailerServiceProvider(app());

    $provider->setClient($client);

    $provider->bootingPackage();
}
