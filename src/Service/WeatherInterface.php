<?php

declare(strict_types=1);

namespace App\Service;

interface WeatherInterface
{
    public function getWeather(array $params): null|array;
}
