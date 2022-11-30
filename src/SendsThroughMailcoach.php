<?php

namespace Spatie\MailcoachMailer;

use Spatie\MailcoachMailer\Headers\ReplacementHeader;
use Spatie\MailcoachMailer\Headers\TransactionalMailHeader;
use Symfony\Component\Mime\Email;

/** @mixin \Illuminate\Mail\Mailable */
trait SendsThroughMailcoach
{
    public function setTransactionalMail(string $mailName): self
    {
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
