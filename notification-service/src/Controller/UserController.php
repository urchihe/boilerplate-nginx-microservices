<?php

declare(strict_types=1);

namespace App\Controller;


use App\UseCase\User\getUserData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UserController extends AbstractController
{
    public function __construct(
        protected readonly getUserData $getUserData,
        private readonly HttpClientInterface $client
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

        return new Response('Data consumed successfully uchenna', 200);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    #[Route("/check-users", name: 'check-users', methods: ["POST"])]
    public function checkUsers(Request $request): Response
    {
        // Retrieve data from the User Service
        $data = $request->request->all();

        $email = $data['email'] ?? '';
        $firstName = $data['firstName'] ?? '';
        $lastName = $data['lastName'] ?? '';

        $response = $this->client->request(
            'POST', 'http://user-service/check-users', [
            'body' => [
                'email' => $email,
                'firstName' => $firstName,
                'lastName' => $lastName
            ],
            "verify_peer" => false,
            "verify_host" => false
        ]);
        $content =  $response->getContent();

        return new Response($content, 200);
    }
}
