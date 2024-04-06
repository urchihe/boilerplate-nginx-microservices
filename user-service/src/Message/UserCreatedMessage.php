<?php

namespace App\Message;

use App\Entity\User;

readonly class UserCreatedMessage
{
    public function __construct(private User $userData)
    {
    }

    public function getUserData(): User
    {
        return $this->userData;
    }
}
