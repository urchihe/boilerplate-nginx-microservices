<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\UserCreatedMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsMessageHandler]
class UserCreatedMessageHandler
{
    public function __construct(
        private LoggerInterface $logger,
        private HttpClientInterface $client,
    )
    {
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function __invoke(UserCreatedMessage $user): void
    {
        $userData = $user->getUserData();

        $response = $this->client->request(
            'POST', 'http://notification-service:9000/users', [
            'json' => [
                'email' => $userData->getEmail(),
                'firstName' => $userData->getFirstName(),
                'lastName' => $userData->getLastName()
            ],
            "verify_peer" => false,
            "verify_host" => false
        ]);
        $content =  $response->getContent();

        $logMessage = sprintf('User created: %s %s <%s>', $userData->getFirstName(), $userData->getLastName(), $userData->getEmail());
        $this->logger->info($logMessage);
        var_dump($content);
    }
}
