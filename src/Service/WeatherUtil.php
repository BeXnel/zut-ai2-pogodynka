<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Location;
use App\Entity\WeatherForecast;
use App\Repository\LocationRepository;

class WeatherUtil
{
    public function __construct(
        private readonly LocationRepository $locationRepository,
    ) {
    }

    /**
     * @return WeatherForecast[]
     */
    public function getWeatherForLocation(Location $location): array
    {
        return $location->getWeatherForecasts()->toArray();
    }

    /**
     * @return WeatherForecast[]
     */
    public function getWeatherForCountryAndCity(
        string $countryCode,
        string $city
    ): array {
        $location = $this->locationRepository->findOneBy([
            'country' => $countryCode,
            'city' => $city,
        ]);

        if ($location === null) {
            return [];
        }

        return $this->getWeatherForLocation($location);
    }
}
