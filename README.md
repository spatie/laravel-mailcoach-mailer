# The driver for sending transactional mails using Mailcoach in Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-mailcoach-mailer.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-mailcoach-mailer)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-mailcoach-mailer.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-mailcoach-mailer)

[Mailcoach](https://mailcoach.app) is an affordable platform for all things mail. It can send campaigns to list of any size. It also provides flexible email automation to set up drip campaigns and more.

Finally, you can also use Mailcoach to send transactional mails. This package contains a driver so you can send any mailable through Mailcoach.

```php
// will be sent through mailcoach

Mail::to('john@example.com')->send(new OrderShippedMail());
```

On [mailcoach.app](https://mailcoach.app), you can see an archive of sent mails.

![screenshot](https://github.com/spatie/laravel-mailcoach-mailer/blob/main/docs/archive.jpg?raw=true)

If you activated the feature, you can also see the opens and clicks of all transactional mails. It's also possible to resend any transactional mails straight from the Mailcoach UI.

Additionally, you'll also be able to create email templates on Mailcoach and use those templates in your app. This is great for marketeers without technical knowledge. They can now write mails without a developer needing to make any code changes.

This is how you would send a mail using a Mailcoach template.

```php
public function build()
{
    $this
        ->mailcoachMail('name-of-your-mailcoach-template')
        ->replacing(['placeholderName' => 'placeHolderValue']);
}
```

If you want to send a mail notification using a mailcoach template, you can do that in the following way.
```php
public function toMail()
{
    return (new MailCoachMessage())
        ->usingMail('name-of-your-mailcoach-template')
        ->replacing(['placeholderName' => 'placeHolderValue']);
}
```

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-mailcoach-mailer.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-mailcoach-mailer)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Documentation

You'll find full documentation on [this page of the Mailcoach docs](https://mailcoach.app/resources/learn-mailcoach/features/transactional).

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
