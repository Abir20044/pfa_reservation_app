<?php

namespace App\Command;

use App\Entity\TimeSlot;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generate-timeslots',
    description: 'Génère les créneaux horaires pour les 30 prochains jours',
)]
class GenerateTimeSlotsCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $startDate = new \DateTimeImmutable();
        $endDate = $startDate->modify('+30 days');
        
        $startTime = new \DateTimeImmutable('09:00');
        $endTime = new \DateTimeImmutable('17:00');
        $duration = 30; // minutes

        $currentDate = clone $startDate;
        $count = 0;

        while ($currentDate <= $endDate) {
            if ($currentDate->format('N') <= 5) { // Du lundi au vendredi
                $currentTime = clone $startTime;
                
                while ($currentTime <= $endTime) {
                    $timeSlot = new TimeSlot();
                    $startDateTime = new \DateTimeImmutable(
                        $currentDate->format('Y-m-d') . ' ' . $currentTime->format('H:i:s')
                    );
                    
                    $timeSlot->setStartTime($startDateTime);
                    $timeSlot->setEndTime($startDateTime->modify("+{$duration} minutes"));
                    $timeSlot->setIsAvailable(true);
                    
                    $this->entityManager->persist($timeSlot);
                    $count++;
                    
                    $currentTime = $currentTime->modify("+{$duration} minutes");
                }
            }
            $currentDate = $currentDate->modify('+1 day');
        }
        
        $this->entityManager->flush();

        $io->success(sprintf('%d créneaux horaires ont été générés avec succès !', $count));

        return Command::SUCCESS;
    }
}
