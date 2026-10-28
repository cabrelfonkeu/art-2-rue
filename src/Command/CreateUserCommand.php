<?php

namespace App\Command;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:create-user', description: 'Crée un compte utilisateur')]
class CreateUserCommand
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher,
    ) {
    }

    public function __invoke(SymfonyStyle $io): int
    {
        $email = $io->ask('Email', null, function (?string $value): string {
            if (empty($value)) {
                throw new \RuntimeException('L\'email est obligatoire.');
            }
            return $value;
        });

        $password = $io->askHidden('Mot de passe', function (?string $value): string {
            if (empty($value)) {
                throw new \RuntimeException('Le mot de passe est obligatoire.');
            }
            return $value;
        });

        $admin = $io->confirm('Donner le rôle administrateur ?', true);

        $user = new Utilisateur();
        $user->setEmail($email);
        $user->setRoles($admin ? ['ROLE_ADMIN'] : []);
        $user->setPassword($this->hasher->hashPassword($user, $password));

        $this->em->persist($user);
        $this->em->flush();

        $io->success('Compte créé : '.$email);

        return Command::SUCCESS;
    }
}
