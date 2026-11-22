<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Location;
use App\Entity\Mesurement;
use App\Repository\LocationRepository;
use App\Repository\MesurementRepository;
use Symfony\Component\ErrorHandler\Debug;

class WeatherUtil
{
    public function __construct(
        private MesurementRepository $mesurementRepository,
        private LocationRepository $locationRepository,
    )
    {
    }

    /**
     * @return Mesurement[]
     */
    public function getWeatherFromLocation(Location $location): array
    {
        return $this->mesurementRepository->findByLocation($location);
    }

    /**
     * @return Mesurement[]
     */
    public function getWeatherFromCountryAndCity(
        String $countryCode,
        string $city,
    ): array
    {
        $location = $this->locationRepository->findOneBy([
            'country' => $countryCode,
            'city' => $city,
            ]
        );
        dump($countryCode,$city);

        if ($location === null) {
            return [];
        }

        return $this->getWeatherFromLocation($location);
    }
}
