<?php

declare(strict_types=1);

namespace App\DataPersister;

use \ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Location;
use App\Entity\Weather;
use App\Service\LocationWeatherService;
use App\Service\OpenWeatherMapService;
use Cmfcmf\OpenWeatherMap\Exception as OWMException;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\RapidApiWeatherService;
use Exception;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class LocationWeatherDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(private readonly LocationWeatherService $locationWeatherService)
    {
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Location;
    }

    /**
     * @throws OWMException
     * @throws Exception
     */
    public function persist($data, array $context = [])
    {
        return $this->locationWeatherService->persistLocationAndWeather($data);
    }

    private function countAvgTemp(float $temp1, float $temp2): float
    {
        return ($temp1 + $temp2) / 2;
    }

    public function remove($data, array $context = [])
    {
        // TODO: Implement remove() method.
    }
}
