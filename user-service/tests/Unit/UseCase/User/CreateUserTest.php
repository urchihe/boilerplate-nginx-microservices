<?php

namespace App\Tests\Unit\UseCase\User;

use App\Tests\Unit\UseCase\UseCaseTestCase;
use App\UseCase\Message\SendMessageToNotificationService;
use App\UseCase\User\CreateUserData;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CreateUserTest extends UseCaseTestCase
{
    private createUserData $createUser;
    private SendMessageToNotificationService $sendMessageToNotificationService;
    public function setUp(): void
    {
        parent::setUp();
        $this->createUser = self::getFromContainer(createUserData::class);
        $this->sendMessageToNotificationService = self::getFromContainer(SendMessageToNotificationService::class);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testCreateUser(): void
    {
        $email = 'test@test.com';
        $firstName = 'testFirstName';
        $lastName = 'testLastName';
        //Test Data is saved in database and returned to api
        $user = $this->createUser->saveUser(email:$email, firstName: $firstName, lastName: $lastName);
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($firstName, $user->getFirstName());
        self::assertEquals($lastName, $user->getLastName());
        //Test Data is received in the notification service
        $messageCode = $this->sendMessageToNotificationService->sendNotification($user);
        self::assertEquals(200, $messageCode);
    }
}
