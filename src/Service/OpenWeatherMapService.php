<?php

namespace App\Service;

use Cmfcmf\OpenWeatherMap;
use Cmfcmf\OpenWeatherMap\Exception as OWMException;
use Exception;
use Http\Adapter\Guzzle6\Client;
use Http\Factory\Guzzle\RequestFactory;

class OpenWeatherMapService implements WeatherInterface
{
    private Client $httpClient;
    private RequestFactory $httpRequestFactory;

    public function __construct(private readonly string $apiKey)
    {
        $this->httpRequestFactory = new RequestFactory();
        $this->httpClient = Client::createWithConfig([]);
    }

    /**
     * @throws OWMException
     * @throws Exception
     */
    public function getWeather(array $params): null|array
    {
        $owm = new OpenWeatherMap($this->apiKey, $this->httpClient, $this->httpRequestFactory);

        try {
            $weatherApi = $owm->getWeather($params['locality'], 'metric', $params['country']);
        } catch (OWMException $e) {
            throw new OWMException('OpenWeatherMap exception: ' . $e->getMessage() . ' (Code ' . $e->getCode() . ').');
        } catch (Exception $e) {
            throw new Exception('General exception: ' . $e->getMessage() . ' (Code ' . $e->getCode() . ').');
        }

        if ($weatherApi) {
            return [
                'lat' => $weatherApi->city->lat,
                'lon' => $weatherApi->city->lon,
                'temperature' => $weatherApi->temperature->max->getValue(),
            ];
        }

        return null;
    }
}
