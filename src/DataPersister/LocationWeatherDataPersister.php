<?php

declare(strict_types=1);

namespace App\DataPersister;

use \ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Location;
use App\Entity\Weather;
use App\Service\OpenWeatherMapService;
use Cmfcmf\OpenWeatherMap\Exception as OWMException;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\RapidApiWeatherService;
use Exception;
use \Symfony\Component\HttpFoundation\Response;

final class LocationWeatherDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly OpenWeatherMapService  $openWeatherMapService,
        private readonly RapidApiWeatherService $rapidApiWeatherService
    )
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
        $openWeather = $this->openWeatherMapService->getWeather(['locality' => $data->getLocality(), 'country' => $data->getCountry()]);

        $rapidWeather = $this->rapidApiWeatherService->getWeather([
            'lon' => $openWeather['lon'],
            'lat' => $openWeather['lat'],
        ]);

        if (!$openWeather && !$rapidWeather) {
            throw new Exception('No Weather data was collected', Response::HTTP_NOT_FOUND);
        }

        $data->setLat($openWeather['lat']);
        $data->setLon($openWeather['lon']);

        $this->entityManager->persist($data);

        $weather = new Weather();
        $weather->setLocation($data);
        $weather->setTemperature1($openWeather['temperature']);
        $weather->setTemperature2($rapidWeather['temperature']);
        $weather->setAvgTemperature($this->countAvgTemp($openWeather['temperature'],$rapidWeather['temperature']));

        $this->entityManager->persist($weather);

        $this->entityManager->flush();

        return $data;
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
