<?php

declare(strict_types=1);

namespace App\Controller;

use App\Enum\DataGroup;
use App\Services\FirebaseService;
use App\UseCase\User\createUserData;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserController extends AbstractController
{
    public function __construct(
        protected readonly createUserData $createUserData,
        private readonly NormalizerInterface $normalizer,
        private readonly LoggerInterface $logger,
        private readonly FirebaseService $firebaseService
    ) {
    }


    /**
     * @throws ExceptionInterface
     */
    #[Route("/users", name: 'users', methods: ["POST"])]
    public function createUser(Request $request): Response
    {
        // Retrieve data from the request body
        $data = $request->request->all();

        // Create a new User entity
        $email = $data['email'] ?? '';
        $firstName = $data['firstName'] ?? '';
        $lastName = $data['lastName'] ?? '';
        //Save the user data in the database and return values
        $user = $this->createUserData->saveUser(email: $email, firstName: $firstName, lastName: $lastName);
        $userSerialize = $this->normalizer->normalize($user, 'json', ['group' => [DataGroup::SHOW_USER]]);
        return new JsonResponse($userSerialize, 200);
    }

    #[Route("/check-users", name: 'check-users', methods: ["POST"])]
    public function checkUser(Request $request): Response
    {
        // Retrieve data from the request body
        $data = $request->request->all();

        // Create a new User entity
        $email = $data['email'] ?? '';
        $firstName = $data['firstName'] ?? '';
        $lastName = $data['lastName'] ?? '';
        //Save the user data in the database and return values
        $logMessage = sprintf('User checked by : %s %s <%s>', $firstName, $lastName, $email);
        $this->logger->critical($logMessage);
        return new JsonResponse($logMessage, 200);
    }

    #[Route("/set-firebase-data", name: 'set-firebase-data', methods: ["POST"])]
    public function setDataFirebase(Request $request): Response
    {
        // Retrieve data from the request body
        $data = $request->request->all();

        // Create a new User entity
        $email = $data['email'] ?? '';
        $firstName = $data['firstName'] ?? '';
        $lastName = $data['lastName'] ?? '';
        //Save the user data in the database and return values
        $this->firebaseService->set('/users', ['email' => $email, 'firstName' => $firstName, 'lastName' => $lastName]);
        return new JsonResponse('Data saved in firebase', 200);
    }

    #[Route("/get-firebase-data", name: 'get-firebase-data', methods: ["POST"])]
    public function getDataFirebase(Request $request): Response
    {
        // Retrieve data from the request body
        $data = $request->request->all();

        //Get the user data in the fireBase
        $data = $this->firebaseService->get('/users');
        return new JsonResponse($data, 200);
    }
}
