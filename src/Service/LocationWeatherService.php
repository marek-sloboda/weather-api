<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Location;
use App\Entity\Weather;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class LocationWeatherService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly OpenWeatherMapService  $openWeatherMapService,
        private readonly RapidApiWeatherService $rapidApiWeatherService,
        private readonly CacheInterface         $cacheLocation,
    )
    {
    }

    public function persistLocationAndWeather(Location $location): Location
    {
        return $this->cacheLocation->get($location->getLocality(), function (ItemInterface $item) use ($location) {

            $openWeather = $this->openWeatherMapService->getWeather(['locality' => $location->getLocality(), 'country' => $location->getCountry()]);

            $rapidWeather = $this->rapidApiWeatherService->getWeather([
                'lon' => $openWeather['lon'],
                'lat' => $openWeather['lat'],
            ]);

            if (!$openWeather && !$rapidWeather) {
                throw new Exception('No Weather data was collected', Response::HTTP_NOT_FOUND);
            }

            $location->setLat($openWeather['lat']);
            $location->setLon($openWeather['lon']);

            $this->entityManager->persist($location);

            $weather = new Weather();
            $weather->setLocation($location);
            $weather->setTemperature1($openWeather['temperature']);
            $weather->setTemperature2($rapidWeather['temperature']);
            $weather->setAvgTemperature($this->countAvgTemp($openWeather['temperature'], $rapidWeather['temperature']));

            $this->entityManager->persist($weather);

            $this->entityManager->flush();

            return $location;
        });
    }

    private function countAvgTemp(float $temp1, float $temp2): float
    {
        return ($temp1 + $temp2) / 2;
    }
}
