<?php

namespace App\Command;

use App\Entity\Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-test-service',
    description: 'Crée un service de test',
)]
class CreateTestServiceCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $service = new Service();
        $service->setName('Coupe Homme');
        $service->setDescription('Coupe de cheveux homme avec shampoing inclus');
        $service->setDuration(30);
        $service->setPrice(2500); // 25.00€
        $service->setIsActive(true);

        $this->entityManager->persist($service);
        $this->entityManager->flush();

        $io->success('Le service de test a été créé avec succès !');

        return Command::SUCCESS;
    }
}
