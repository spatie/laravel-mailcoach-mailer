<?php

namespace Spatie\MailcoachMailer\Concerns;

use Spatie\MailcoachMailer\Headers\ReplacementHeader;
use Spatie\MailcoachMailer\Headers\TransactionalMailHeader;
use Symfony\Component\Mime\Email;

/** @mixin \Illuminate\Mail\Mailable */
trait UsesMailcoachMail
{
    private bool $usingMailcoachMail = false;

    public function mailcoachMail(string $mailName, array $replacements = []): self
    {
        $this->usingMailcoachMail = true;

        $this->html = 'use-mailcoach-mail';

        $this->replacing($replacements);

        $this->withSymfonyMessage(function (Email $email) use ($mailName) {
            $header = new TransactionalMailHeader($mailName);

            if ($email->getHeaders()->has($header->getName())) {
                $email->getHeaders()->remove($header->getName());
            }

            $email->getHeaders()->add($header);
        });

        return $this;
    }

    public function replacing(array|string $key, string $value = null): self
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
