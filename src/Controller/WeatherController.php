<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\MesurementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
    #[Route('/weather/{city}/{country}', name: 'app_weather',defaults: ['country' => 'PL'])]
    public function city(Location $location, MesurementRepository $repository): Response
    {
        $mesurements = $repository->findByLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'mesurements' => $mesurements,
        ]);
    }


}
