<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use Doctrine\ORM\EntityManagerInterface;

class FeatureTestCase extends ApiTestCase
{
    protected $client;
    protected $entityManager;

    public function setUp(): void
    {
        $this->client = self::createClient([], ['headers' => ['Content-Type' => 'application/json']]);
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
    }
}
