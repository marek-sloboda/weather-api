<?php

declare(strict_types=1);

namespace Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class LocationCreateResourceTest extends ApiTestCase
{

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->client = self::createClient();
    }

    /**
     * @throws TransportExceptionInterface
     * @return void
     */
    public function testCreateLocation(): void
    {
        $this->client->request('POST', 'api/locations', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'locality' => 'Gorzów',
                'country' => 'pl',
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
    }
}
