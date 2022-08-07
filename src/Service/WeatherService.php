<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Weather;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class WeatherService
{
    public function __construct(
        private readonly CacheInterface         $cacheWeather,
        private readonly EntityManagerInterface $em
    )
    {
    }

    public function getWeatherForLocality(string $locationId): Weather
    {
        return $this->cacheWeather->get('weather_locality_' . $locationId, function (ItemInterface $item) use ($locationId) {
            return $this->em->getRepository(Weather::class)->findOneBy(['location'=>$locationId]);
        });
    }
}
