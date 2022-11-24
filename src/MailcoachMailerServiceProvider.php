<?php

namespace Spatie\MailcoachMailer;

use Illuminate\Support\Facades\Mail;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\MailcoachMailer\Commands\SendTestMailCommand;
use Spatie\MailcoachMailer\Exceptions\InvalidMailerConfig;
use Symfony\Component\Mailer\Transport\Dsn;

class MailcoachMailerServiceProvider extends PackageServiceProvider
{
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

            return (new MailcoachTransportFactory)->create(
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
}
