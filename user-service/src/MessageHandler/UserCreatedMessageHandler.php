<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\UserCreatedMessage;
use App\UseCase\Message\SendMessageToNotificationService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsMessageHandler]
class UserCreatedMessageHandler
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly SendMessageToNotificationService $sendMessageToNotificationService,
    ) {
    }

    /**
     * @param UserCreatedMessage $user
     * @throws TransportExceptionInterface
     */
    public function __invoke(UserCreatedMessage $user): void
    {
        $userData = $user->getUserData();
        $statusCode = $this->sendMessageToNotificationService->sendNotification(userData: $userData);

        $logMessage = sprintf(
            'User created: %s %s <%s> statusCode <%s>',
            $userData->getFirstName(),
            $userData->getLastName(),
            $userData->getEmail(),
            $statusCode
        );
        $this->logger->info($logMessage);
    }
}
