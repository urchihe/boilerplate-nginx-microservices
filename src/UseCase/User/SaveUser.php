<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class SaveUser
{
    public function __construct(
        private EntityManagerInterface $entityManager,
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
//        $errors = $this->validator->validate($user);
//
//        if (count($errors) > 0) {
//            /*
//             * Uses a __toString method on the $errors variable which is a
//             * ConstraintViolationList object. This gives us a nice string
//             * for debugging.
//             */
//            $errorsString = (string) $errors;
//
//            return [$errorsString, Response::HTTP_UNPROCESSABLE_ENTITY];
//        }
        // Save the user to the database
        $entityManager = $this->entityManager;
        $entityManager->persist($user);
        $entityManager->flush();
        return $user;
    }
}
