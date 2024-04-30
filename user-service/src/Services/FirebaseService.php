<?php

namespace App\Services;

use App\Config\FirebaseConfig;
use Firebase\FirebaseLib;

class FirebaseService
{
    private FirebaseLib $firebase;
    public function __construct(protected FirebaseConfig $firebaseConfig)
    {

        $this->firebase = new FirebaseLib($firebaseConfig->getAuthAdmin(), $firebaseConfig->getAppKey());
    }

    public function set(string $path, mixed $value): void
    {
        $this->firebase->set($path, $value);
    }

    public function get(string $path): mixed
    {
        return $this->firebase->get($path);
    }

    public function delete(string $path): void
    {
        $this->firebase->delete($path);
    }
    public function update(string $path, mixed $value): void
    {
        $this->firebase->update($path, $value);
    }
    public function push(string $path, mixed $value): void
    {
        $this->firebase->push($path, $value);
    }
    public function setToken(string $token): void
    {
        $this->firebase->setToken($token);
    }
    public function setBaseUrl(string $path): void
    {
        $this->firebase->setBaseURI($path);
    }
    public function setTimeOut(int $time): void
    {
        $this->firebase->setTimeOut($time);
    }
}
