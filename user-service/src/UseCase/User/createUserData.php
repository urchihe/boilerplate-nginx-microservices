<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Entity\User;
use App\Message\UserCreatedMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class createUserData
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        protected MessageBusInterface $messageBus,
        //private readonly ValidatorInterface $validator
    ) {
    }

    public function saveUser(string $email, string $firstName, string $lastName): User
    {
        // Create a new User entity
        $user = new User();
        $user->setEmail($email);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);

        // Save the user to the database
        $entityManager = $this->entityManager;
        $entityManager->persist($user);
        $entityManager->flush();

        // Dispatch created message to the MessageBus(Broker)
        $this->messageBus->dispatch(new UserCreatedMessage($user));
        return $user;
    }
}
