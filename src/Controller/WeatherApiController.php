<?php

namespace App\Controller;

use App\Entity\Mesurement;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(
        #[MapQueryParameter] string $city,
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false,
        WeatherUtil $weatherUtil,
    ): Response {
        $measurements = $weatherUtil->getWeatherFromCountryAndCity($country, $city);

        switch ($format) {
            case 'csv':

                if ($twig) {
                    return $this->render('weather_api/index.csv.twig', [
                        'city' => $city,
                        'country' => $country,
                        'measurements' => $measurements,
                    ]);
                }

                $rows = [['city', 'country', 'date', 'celsius', 'fahrenheit']];

                foreach ($measurements as $measurement) {
                    $rows[] = [
                        $city,
                        $country,
                        $measurement->getDate()?->format('Y-m-d') ?? '',
                        $measurement->getCelsius() ?? '',
                        $measurement->getFahrenheit() ?? '',
                    ];
                }

                $csv = implode("\n", array_map(
                    static fn(array $row): string => implode(',', array_map(
                        static fn(?string $value): string => sprintf('%s', str_replace('', '', (string) $value)),
                        $row,
                    )),
                    $rows,
                ));

                return new Response($csv, Response::HTTP_OK, [
                    'Content-Type' => 'text/csv; charset=UTF-8',
                ]);

            case 'json':
                if ($twig) {
                    return $this->render('weather_api/index.json.twig', [
                        'city' => $city,
                        'country' => $country,
                        'measurements' => $measurements,
                    ]);
                }
                return $this->json([
                    'city' => $city,
                    'country' => $country,
                    'measurements' => array_map(
                        fn(Mesurement $m) => [
                            'date' => $m->getDate()?->format('Y-m-d'),
                            'celcius' => $m->getCelsius(),
                            'fahrenheit' => $m->getFahrenheit(),
                        ],
                        $measurements,
                    ),
                ]);

            default:
                return new Response('Format must be json or csv.', Response::HTTP_BAD_REQUEST);
        }
    }
}
