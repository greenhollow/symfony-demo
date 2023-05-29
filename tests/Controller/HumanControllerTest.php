<?php

namespace App\Tests\Controller;

use App\Entity\Human;
use App\Tests\FeatureTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group feature
 */
class HumanControllerTest extends FeatureTestCase
{
    public function testCreateWithProhibitedUuidInput(): void
    {
        // post to create a new human
        $response = $this->client->request('POST', '/api/humans', ['body' => json_encode([
            'uuid' => '11111111-2222-3333-4444-555555555555',
            'name' => 'Some Guy',
        ])]);

        // confirm the expected response
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals(['@context', '@id', '@type'], array_keys($data));
        $this->assertEquals('/api/contexts/Human', $data['@context']);

        // confirm the UUID is exposed as the identifier
        $this->assertMatchesRegularExpression(
            '/^\/api\/humans\/[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/',
            $data['@id']
        );

        // confirm the posted UUID was ignored
        $this->assertNotEquals('/api/humans/11111111-2222-3333-4444-555555555555', $data['@id']);
    }

    public function testGetOneByUuid(): void
    {
        // create a human
        $human = new Human();
        $human->setName('Some Gal');
        $this->entityManager->persist($human);
        $this->entityManager->flush();

        // request the human by UUID
        $response = $this->client->request('GET', sprintf('/api/humans/%s', $human->getUuid()));

        // confirm the expected response
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testGetAll(): void
    {
        // create some humans
        foreach (['Some Gal', 'Some Guy'] as $name) {
            $human = new Human();
            $human->setName($name);
            $this->entityManager->persist($human);
        }
        $this->entityManager->flush();

        // request all the humans
        $response = $this->client->request('GET', '/api/humans');

        // confirm the expected response
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $data = json_decode($response->getContent(), true);
        $this->assertEquals(['humans', 'metadata', 'links'], array_keys($data));
        $this->assertCount(2, $data['humans']);
    }
}
