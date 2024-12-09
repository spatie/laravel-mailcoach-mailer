<?php

namespace Spatie\MailcoachMailer\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Arr;
use Spatie\MailcoachMailer\Headers\FakeHeader;
use Spatie\MailcoachMailer\Headers\MailerHeader;
use Spatie\MailcoachMailer\Headers\ReplacementHeader;
use Spatie\MailcoachMailer\Headers\TransactionalMailHeader;
use Symfony\Component\Mime\Email;

class MailcoachMessage extends MailMessage
{
    public string $mailName;

    public array $replacements = [];

    public bool $fake = false;

    public function usingMail(string $mailName): self
    {
        $this->mailName = $mailName;

        $this->withSymfonyMessage(function (Email $email) use ($mailName) {
            $transactionalHeader = new TransactionalMailHeader($mailName);

            if ($email->getHeaders()->has($transactionalHeader->getName())) {
                $email->getHeaders()->remove($transactionalHeader->getName());
            }

            $email->getHeaders()->add($transactionalHeader);
        });

        return $this;
    }

    public function usingMailer(string $mailer): self
    {
        $this->withSymfonyMessage(function (Email $email) use ($mailer) {
            $mailerHeader = new MailerHeader($mailer);

            if ($email->getHeaders()->has($mailerHeader->getName())) {
                $email->getHeaders()->remove($mailerHeader->getName());
            }

            $email->getHeaders()->add($mailerHeader);
        });

        return $this;
    }

    public function replacing(array|string $key, string|array|null $value = null): self
    {
        if (is_array($key)) {
            foreach ($key as $realKey => $value) {
                $this->replacing($realKey, $value);
            }

            return $this;
        }

        Arr::set($this->replacements, $key, $value);

        $this->withSymfonyMessage(function (Email $email) use ($key, $value) {
            $email->getHeaders()->add(new ReplacementHeader($key, $value));
        });

        return $this;
    }

    public function faking(bool $value): self
    {
        $this->fake = $value;

        $this->withSymfonyMessage(function (Email $email) {
            $fakeHeader = new FakeHeader($mailer);

            if ($email->getHeaders()->has($fakeHeader->getName())) {
                $email->getHeaders()->remove($fakeHeader->getName());
            }

            $email->getHeaders()->add($fakeHeader);
        });

        return $this;
    }
}
