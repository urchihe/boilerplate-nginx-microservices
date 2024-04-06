<?php

namespace App\Tests\Unit\UseCase;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UseCaseTestCase extends WebTestCase
{
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        assert($entityManager instanceof EntityManager);

        $this->entityManager = $entityManager;
        $this->entityManager->beginTransaction();
        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->entityManager->rollBack();
        parent::tearDown();
    }

    /** @noinspection PhpMixedReturnTypeCanBeReducedInspection */
    protected function getFromContainer(string $class): mixed
    {
        $object = self::getContainer()->get($class);
        assert($object instanceof $class);

        return $object;
    }

    /**
     * @param string[] $parameters
     * @throws ReflectionException
     */
    protected function invokePrivateMethod(mixed $object, string $methodName, array $parameters = []): void
    {
        $reflection = new ReflectionClass($object);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        $method->invokeArgs($object, $parameters);
    }


}