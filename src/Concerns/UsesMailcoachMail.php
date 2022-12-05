<?php

namespace Spatie\MailcoachMailer\Concerns;

use Spatie\MailcoachMailer\Headers\ReplacementHeader;
use Spatie\MailcoachMailer\Headers\TransactionalMailHeader;
use Symfony\Component\Mime\Email;

/** @mixin \Illuminate\Mail\Mailable */
trait UsesMailcoachMail
{
    public function mailcoachMail(string $mailName, array $replacements = []): self
    {
        $this->html = 'use-mailcoach-mail';

        $this->addReplacements($replacements);

        $this->withSymfonyMessage(function (Email $email) use ($mailName) {
            $header = new TransactionalMailHeader($mailName);

            if ($email->getHeaders()->has($header->getName())) {
                $email->getHeaders()->remove($header->getName());
            }

            $email->getHeaders()->add($header);
        });

        return $this;
    }

    public function addReplacement(string $key, string $value): self
    {
        $this->withSymfonyMessage(function (Email $email) use ($key, $value) {
            $email->getHeaders()->add(new ReplacementHeader($key, $value));
        });

        return $this;
    }

    public function addReplacements(array $replacements): self
    {
        foreach ($replacements as $key => $value) {
            $this->addReplacement($key, $value);
        }

        return $this;
    }
}
