<?php

namespace Spatie\MailcoachMailer\Concerns;

use Spatie\MailcoachMailer\Headers\MailerHeader;
use Spatie\MailcoachMailer\Headers\ReplacementHeader;
use Spatie\MailcoachMailer\Headers\TransactionalMailHeader;
use Symfony\Component\Mime\Email;

/** @mixin \Illuminate\Mail\Mailable */
trait UsesMailcoachMail
{
    private bool $usingMailcoachMail = false;

    public function mailcoachMail(string $mailName, array $replacements = [], ?string $mailer = null): self
    {
        $this->usingMailcoachMail = true;

        $this->html = 'use-mailcoach-mail';

        $this->replacing($replacements);
        $this->usingMailer($mailer);

        $this->withSymfonyMessage(function (Email $email) use ($mailName) {
            $transactionalHeader = new TransactionalMailHeader($mailName);

            if ($email->getHeaders()->has($transactionalHeader->getName())) {
                $email->getHeaders()->remove($transactionalHeader->getName());
            }

            $email->getHeaders()->add($transactionalHeader);
        });

        return $this;
    }

    public function usingMailer(?string $mailer): self
    {
        if (! $mailer) {
            return $this;
        }

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

        $this->withSymfonyMessage(function (Email $email) use ($key, $value) {
            $email->getHeaders()->add(new ReplacementHeader($key, $value));
        });

        return $this;
    }

    protected function buildSubject($message): self
    {
        if (! $this->usingMailcoachMail) {
            return parent::buildSubject($message);
        }

        if ($this->subject) {
            $message->subject($this->subject);
        }

        return $this;
    }
}
