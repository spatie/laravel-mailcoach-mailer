<?php

namespace Spatie\MailcoachMailer;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\MailcoachMailer\Commands\MailcoachMailerCommand;

class MailcoachMailerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-mailcoach-mailer')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-mailcoach-mailer_table')
            ->hasCommand(MailcoachMailerCommand::class);
    }
}
