<?php

namespace App\Tests\Entity;

use App\Entity\Human;
use App\Tests\IntegrationTestCase;

/**
 * @group integration
 */
class HumanTest extends IntegrationTestCase
{
    public function testCreatingAHuman(): void
    {
        // create a human
        $human = new Human();
        $this->entityManager->persist($human);
        $this->entityManager->flush();

        // confirm the human was created
        $this->assertTrue(is_int($human->getId()));
        $this->assertMatchesRegularExpression('/[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}/',$human->getUuid());
        $this->assertEquals(0, strlen($human->getName()));
    }
}
