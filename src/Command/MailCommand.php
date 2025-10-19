<?php

namespace App\Command;

use App\Repository\UserRepository;
use App\Repository\VideoGameRepository;
use App\Services\MailerService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:send-newsletter',
    description: 'Envoie la newsletter hebdomadaire'
)]
class MailCommand extends Command
{
    public function __construct(
        private UserRepository $users,
        private VideoGameRepository $games,
        private MailerService $mailer
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $subscribers = $this->users->getNewsletterUser();
        if (!$subscribers) {
            $output->writeln('Aucun abonné.');
            return Command::SUCCESS;
        }

        $upcomingGames = $this->games->findReleaseGamesNextSevenDays();

        foreach ($subscribers as $user) {
            $this->mailer->sendEmail(
                to: $user->getEmail(),
                subject: 'Les sorties de la semaine',
                context: ['games' => $upcomingGames],
                template: 'mail.html.twig'
            );
        }

        $output->writeln(sprintf('Newsletter envoyée aux ', count($subscribers), ' abonnés.'));
        return Command::SUCCESS;
    }
}
