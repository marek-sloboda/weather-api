<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\SaveLocation;
use App\Service\LocationWeatherService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SaveLocationHandler implements MessageHandlerInterface
{
    public function __construct(private readonly LocationWeatherService $locationWeatherService)
    {
    }

    public function __invoke(SaveLocation $saveLocation)
    {
        $location = $saveLocation->getLocation();

        $locationSaved = $this->locationWeatherService->persistLocationAndWeather($location);
    }
}
