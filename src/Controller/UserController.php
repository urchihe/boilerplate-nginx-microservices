<?php

declare(strict_types=1);

namespace App\Controller;

use App\Enum\DataGroup;
use App\Message\UserCreatedMessage;
use App\UseCase\User\SaveUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserController extends AbstractController
{
    public function __construct(
        protected readonly SaveUser $saveUser,
        private readonly NormalizerInterface $normalizer,
        protected MessageBusInterface $messageBus,
    ) {
    }


    /**
     * @throws ExceptionInterface
     */
    #[Route("/users", name: 'users', methods: ["POST"])]
    public function user(Request $request): Response
    {
        // Retrieve data from the request body
        $data = $request->request->all();

        // Create a new User entity
        $email = $data['email'] ?? '';
        $firstName = $data['firstName'] ?? '';
        $lastName = $data['lastName'] ?? '';
        //Save the user data in the database
        $user = $this->saveUser->saveUser(email: $email, firstName: $firstName, lastName: $lastName);

        $userSerialize = $this->normalizer->normalize($user, 'json', ['group' => [DataGroup::SHOW_USER]]);
        // Dispatch created message to the MessageBus(Broker)
        $this->messageBus->dispatch(new UserCreatedMessage($user));
        return new JsonResponse($userSerialize, 200);
    }
}
