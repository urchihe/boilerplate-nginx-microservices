<?php

declare(strict_types=1);

namespace App\UseCase\User;

use Psr\Log\LoggerInterface;

readonly class GetUserData
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function logUserData(string $email, string $firstName, string $lastName): void
    {
        // Dispatch created message to the MessageBus(Broker)
        $logMessage = sprintf('User created: %s %s <%s>', $firstName, $lastName, $email);
        $this->logger->info($logMessage);
    }
}
