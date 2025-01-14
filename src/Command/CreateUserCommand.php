<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Supprimer l'ancien utilisateur s'il existe
        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin@admin.com']);
        if ($existingUser) {
            $this->entityManager->remove($existingUser);
            $this->entityManager->flush();
        }

        // Créer un nouvel utilisateur
        $user = new User();
        $user->setEmail('admin@admin.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setFirstName('Admin');
        $user->setLastName('User');

        // Hash le mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'admin123');
        $user->setPassword($hashedPassword);

        // Créer un nouvel utilisateur ordinaire
        $ordinaryUser = new User();
        $ordinaryUser->setEmail('user@example.com');
        $ordinaryUser->setRoles(['ROLE_USER']);
        $ordinaryUser->setFirstName('Ordinary');
        $ordinaryUser->setLastName('User');

        // Hash le mot de passe pour l'utilisateur ordinaire
        $hashedOrdinaryPassword = $this->passwordHasher->hashPassword($ordinaryUser, 'user123');
        $ordinaryUser->setPassword($hashedOrdinaryPassword);

        // Persist both users
        $this->entityManager->persist($user);
        $this->entityManager->persist($ordinaryUser);
        $this->entityManager->flush();

        $output->writeln('Users created successfully!');

        return Command::SUCCESS;
    }
}
