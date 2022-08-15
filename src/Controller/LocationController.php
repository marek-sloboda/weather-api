<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationType;
use App\Message\SaveLocation;
use App\Service\LocationWeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class LocationController extends AbstractController
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {

    }

    #[Route('/location', name: 'app_location')]
    public function index(Request $request): Response
    {
        $location = new Location();

        $form = $this->createForm(LocationType::class, $location);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Location $locationSubmited */
            $locationSubmited = $form->getData();

            $locationMessage = new SaveLocation($locationSubmited);

            $this->messageBus->dispatch($locationMessage);

            return $this->redirectToRoute('app_weather', ['locality' => urlencode($locationSubmited->getLocality())]);
        }

        return $this->renderForm('location.html.twig', [
            'form' => $form,
        ]);
    }
}
