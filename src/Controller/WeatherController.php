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

    #[Route('location/{id}/weather', name: 'app_weather')]
    public function index(string $id): Response
    {
        /** @var Weather $weather */
        $weather = $this->weatherService->getWeatherForLocality($id);
        $location = $this->locationRepository->find($id);
        return $this->render('weather.html.twig', [
            'weather' => $weather,
            'location' => $location,
        ]);
    }
}
