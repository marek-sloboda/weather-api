<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationType;
use App\Service\LocationWeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocationController extends AbstractController
{
    public function __construct(private readonly LocationWeatherService $locationWeatherService)
    {

    }

    #[Route('/location', name: 'app_location')]
    public function index(Request $request): Response
    {
        $location = new Location();

        $form = $this->createForm(LocationType::class, $location);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $locationSubmited = $form->getData();

            $locationSaved = $this->locationWeatherService->persistLocationAndWeather($locationSubmited);

            return $this->redirectToRoute('app_weather', ['id' => $locationSaved->getId()]);
        }

        return $this->renderForm('location.html.twig', [
            'form' => $form,
        ]);
    }
}
