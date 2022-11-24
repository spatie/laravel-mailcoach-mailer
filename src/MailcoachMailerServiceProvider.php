<?php

namespace Spatie\MailcoachMailer;

use Illuminate\Support\Facades\Mail;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\MailcoachMailer\Commands\SendTestMailCommand;
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
        Mail::extend('mailcoach', function() {
            $this->validateConfig();

            return (new MailcoachTransportFactory)->create(
                new Dsn(
                    'mailcoach',
                    config('mail.mailers.mailcoach.domain'),
                    options: [
                        'token' => config('mail.mailers.mailcoach.api_token'),
                    ],
                )
            );
        });
    }

    protected function validateConfig(): void
    {
        collect([
            'mail.mailers.mailcoach',
            'mail.mailers.mailcoach.domain',
            'mail.mailers.mailcoach.token',
        ])->each(function(string $configKey) {
            if (empty(config($configKey))) {
                throw new \Exception("You must set the `$configKey` config option.");
            }
        });
    }
}
