<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\MesurementRepository;
use App\Service\WeatherUtil;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
    #[Route('/weather/{country}/{city}', name: 'app_weather',defaults: ['country' => 'PL'])]
    public function city(
        #[MapEntity(mapping:['country'=>'country','city'=>'city'])]
        Location $location,
        WeatherUtil $util,
    ): Response
    {
        $mesurements = $util->getWeatherFromLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'mesurements' => $mesurements,
        ]);
    }


}
