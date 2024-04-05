<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\UserCreatedMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UserCreatedMessageHandler
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function __invoke(UserCreatedMessage $user): void
    {
        $userData = $user->getUserData();
        $logMessage = sprintf('User created: %s %s <%s>', $userData->getFirstName(), $userData->getLastName(), $userData->getEmail());
        $this->logger->info($logMessage);
    }
}
