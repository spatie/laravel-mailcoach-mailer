<?php

namespace Spatie\MailcoachMailer\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\MailcoachMailer\MailcoachMailerServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            MailcoachMailerServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');

        view()->addNamespace('mailcoach-mailer', __DIR__ . '/TestSupport/resources/views');
    }
}
