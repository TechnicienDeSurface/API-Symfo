<?php

namespace App\Services;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailerService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Envoie un email avec une vue Twig
     */
    public function sendEmail(
        string $to,
        string $subject,
        array $context = [],
        string $template = 'templates/mail.html.twig'
    ): void {
        $email = (new TemplatedEmail())
            ->from(new Address('no-reply@example.com', 'Mon Application'))
            ->to(new Address($to))
            ->subject($subject)
            ->htmlTemplate($template)
            ->context($context);

        $this->mailer->send($email);
    }
}
