<?php

namespace App\Config;

class FirebaseConfig
{
    public function __construct(
        protected string $appKey,
        protected string $authDomain,
        protected string $projectId,
        protected string $storageBucket,
        protected string $messagingSenderId,
        protected string $appId,
        protected string $measurementId,
    ) {
    }

    public function getAppKey(): string
    {
        return $this->appKey;
    }

    public function getAuthAdmin(): string
    {
        return $this->authDomain;
    }
    public function getProjectId(): string
    {
        return $this->projectId;
    }
    public function getStorageBucket(): string
    {
        return $this->storageBucket;
    }
    public function getMessagingSendingId(): string
    {
        return $this->messagingSenderId;
    }
    public function getFirebaseAppId(): string
    {
        return $this->appId;
    }
    public function getMeasurementId(): string
    {
        return $this->measurementId;
    }
}
