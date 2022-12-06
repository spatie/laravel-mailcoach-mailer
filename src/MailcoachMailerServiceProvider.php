<?php

namespace Spatie\MailcoachMailer;

use Illuminate\Support\Facades\Mail;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\MailcoachMailer\Commands\SendTestMailCommand;
use Spatie\MailcoachMailer\Exceptions\InvalidMailerConfig;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MailcoachMailerServiceProvider extends PackageServiceProvider
{
    protected ?HttpClientInterface $client = null;

    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-mailcoach-mailer')
            ->hasViews()
            ->hasCommand(SendTestMailCommand::class);
    }

    public function bootingPackage()
    {
        Mail::extend('mailcoach', function (array $config) {
            $this->validateConfig($config);

            return (new MailcoachTransportFactory(
                client: $this->client,
            ))->create(
                new Dsn(
                    'mailcoach',
                    $config['domain'],
                    options: [
                        'token' => $config['token'],
                    ],
                )
            );
        });
    }

    protected function validateConfig($mailConfig): void
    {
        collect([
            'domain',
            'token',
        ])->each(function (string $key) use ($mailConfig) {
            if (! array_key_exists($key, $mailConfig)) {
                throw InvalidMailerConfig::missingKey($key);
            }

            if (is_null($mailConfig[$key])) {
                throw InvalidMailerConfig::missingValue($key);
            }
        });
    }

    public function setClient(HttpClientInterface $client): self
    {
        $this->client = $client;

        return $this;
    }
}
