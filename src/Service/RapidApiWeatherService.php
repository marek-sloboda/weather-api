<?php

declare(strict_types=1);

namespace App\Service;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use JetBrains\PhpStorm\ArrayShape;

class RapidApiWeatherService implements WeatherInterface
{
    private Client $client;

    public function __construct(private readonly string $apiKey)
    {
        $this->client = new Client();
    }

    /**
     * @throws Exception
     */
    public function getWeather(array $params): null|array
    {
        try {
            $response = $this->client->get($this->getUrl($params),
                [
                    'headers' => $this->getHeaders(),
                ]
            );
        } catch (ClientException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }

        if ($response) {

            $weather = json_decode($response->getBody()->getContents());

            return [
                'temperature' => $weather->data[0]->temp,
            ];
        }

        return null;
    }

    #[ArrayShape(['X-RapidAPI-Key' => "string", 'X-RapidAPI-Host' => "string", 'Accept' => "string", 'Content-type' => "string"])]
    private function getHeaders(): array
    {
        return [
            'X-RapidAPI-Key' => $this->apiKey,
            'X-RapidAPI-Host' => $this->getHost(),
            'Accept' => 'application/json',
            'Content-type' => 'application/json'
        ];
    }

    private function getUrl($params): string
    {
        return 'https://' . $this->getHost() . '/current?' . http_build_query($params);
    }

    private function getHost(): string
    {
        return 'weatherbit-v1-mashape.p.rapidapi.com';
    }
}
