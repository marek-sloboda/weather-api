<?php

namespace App\Controller;

use App\Entity\Weather;
use App\Repository\LocationRepository;
use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    public function __construct(
        private readonly WeatherService     $weatherService,
        private readonly LocationRepository $locationRepository,
    )
    {
    }

    #[Route('location/{locality}/weather', name: 'app_weather')]
    public function index(string $locality): Response
    {
        $locations = $this->locationRepository->findBy(['locality' => urldecode($locality)]);

        if(empty($locations)){
            throw $this->createNotFoundException('Not found weather for location');
        }

        sort($locations);
        $location = end($locations);

        $weather = $this->weatherService->getWeatherForLocality($location->getId());

        return $this->render('weather.html.twig', [
            'weather' => $weather,
            'location' => $location,
        ]);
    }
}
