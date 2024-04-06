<?php

declare(strict_types=1);

namespace App\Controller;


use App\UseCase\User\getUserData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(
        protected readonly getUserData $getUserData
    ) {
    }


    /**
     */
    #[Route("/users", name: 'users', methods: ["POST"])]
    public function getUsers(Request $request): Response
    {
        // Retrieve data from the User Service
        $data = $request->request->all();

        $email = $data['email'] ?? '';
        $firstName = $data['firstName'] ?? '';
        $lastName = $data['lastName'] ?? '';

        //Log the user data
        $this->getUserData->logUserData(email: $email, firstName: $firstName, lastName: $lastName);

        return new Response('Data consumed successfully', 200);
    }
}
