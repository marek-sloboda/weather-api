<?php

declare(strict_types=1);

namespace App\DataProvider;


use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\SubresourceDataProviderInterface;
use App\Entity\Weather;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class WeatherItemDataProvider implements RestrictedDataProviderInterface, SubresourceDataProviderInterface
{
    private bool $alreadyInvoked = false;

    public function __construct(
        private readonly CacheInterface         $cacheWeather,
        private readonly EntityManagerInterface $em,
    )
    {
    }

    public function getSubresource(string $resourceClass, array $identifiers, array $context, string $operationName = null): ?Weather
    {
        $this->alreadyInvoked = true;

        return $this->cacheWeather->get('location-' . $context['subresource_identifiers']['id'], function (ItemInterface $item) use ($resourceClass, $identifiers, $context, $operationName) {
            return $this->em->getRepository($resourceClass)->findOneBy(['location' => $context['subresource_identifiers']['id']]);
        });
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return !$this->alreadyInvoked && Weather::class === $resourceClass;
    }
}
