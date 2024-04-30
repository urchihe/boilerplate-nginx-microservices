<?php

namespace App\UseCase\Message;

use App\Entity\User;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class SendMessageToNotificationService
{
    public function __construct(private HttpClientInterface $client)
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendNotification(User $userData): int
    {

        $response = $this->client->request(
            'POST',
            'http://notification-service/users',
            [
            'json' => [
                'email' => $userData->getEmail(),
                'firstName' => $userData->getFirstName(),
                'lastName' => $userData->getLastName()
            ],
            "verify_peer" => false,
            "verify_host" => false
            ]
        );

        return $response->getStatusCode();
    }
}
