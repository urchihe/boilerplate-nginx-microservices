<?php

namespace App\Tests\Unit\UseCase\User;

use App\Tests\Unit\UseCase\UseCaseTestCase;
use App\UseCase\User\getUserData;

class CreateUserTest extends UseCaseTestCase
{
    private getUserData $createUser;
    public function setUp(): void
    {
        parent::setUp();
        $this->createUser = self::getFromContainer(getUserData::class);
    }

    public function testCreateUser(): void
    {
        $email = 'test@test.com';
        $firstName = 'testFirstName';
        $lastName = 'testLastName';
        //Test Data is saved in database and returned to api
        $user = $this->createUser->saveUser(email:$email, firstName: $firstName, lastName: $lastName);
        self::assertEquals($user->getEmail(), $email);
        self::assertEquals($user->getFirstName(), $firstName);
        self::assertEquals($user->getLastName(), $lastName);
        //Test Data is in rabbitQM broker dispatched CreateUserMessage
        //Test Data is CreateUserMessageHandler consumed the data
    }
}
