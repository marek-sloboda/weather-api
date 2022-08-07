<?php

declare(strict_types=1);

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Weather;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class WeatherItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(
        private readonly CacheInterface         $cacheWeather,
        private readonly EntityManagerInterface $em
    )
    {
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?Weather
    {
        return $this->cacheWeather->get('weather_' . $id, function (ItemInterface $item) use ($resourceClass, $id) {
            return $this->em->getRepository($resourceClass)->find($id);
        });
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Weather::class === $resourceClass;
    }
}
