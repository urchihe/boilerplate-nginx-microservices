<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\TestHistoryState;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
        TestHistoryState::disable();
        $this->purge();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 20; $i++) {
             $user = new User();
             $user->setEmail("test$i@test.com");
             $user->setFirstName("Uchenna$i");
             $user->setLastName("Ihe$i");
             $manager->persist($user);
        }

        $manager->flush();
    }

    public function purge(): void
    {
        $users = $this->userRepository->findAll();
        foreach ($users as $user) {
            $this->entityManager->remove($user);
        }

        $this->entityManager->flush();
    }
}
