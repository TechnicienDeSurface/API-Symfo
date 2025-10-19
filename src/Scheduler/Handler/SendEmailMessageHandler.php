<?php

namespace App\Scheduler\Handler;

use App\Scheduler\Message\SendEmailMessage;
use App\Repository\UserRepository;
use App\Repository\VideoGameRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Address;

#[AsMessageHandler]
class SendEmailMessageHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private VideoGameRepository $videoGameRepository,
        private MailerInterface $mailer
    ) {}
    
    public function __invoke(SendEmailMessage $message): void
    {
        // Récupérer les abonnés
        $subscribers = $this->userRepository->findBy(['newsletter' => true]);
        
        if (empty($subscribers)) {
            return;
        }
        
        // Récupérer les jeux des 7 prochains jours
        $games = $this->videoGameRepository->findGamesNextSevenDays();
        
        // Envoyer l'email à chaque abonné
        foreach ($subscribers as $subscriber) {
            try {
                $email = (new TemplatedEmail())
                    ->from(new Address('no-reply@example.com', 'Newsletter Jeux'))
                    ->to($subscriber->getEmail())
                    ->subject('Les sorties à venir')
                    ->htmlTemplate('mail.html.twig')
                    ->context(['games' => $games]);
                
                $this->mailer->send($email);
            } catch (\Exception $e) {
                // Logger l'erreur si nécessaire
            }
        }
    }
}