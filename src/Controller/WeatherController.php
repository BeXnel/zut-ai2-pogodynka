<?php

namespace App\Controller;

use App\Entity\Location;
use App\Service\WeatherUtil;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
    #[Route('/weather/{country}/{city}', name: 'app_weather')]
    public function city(
        #[MapEntity(mapping: ['country' => 'country', 'city' => 'city'])]
        Location $location,
        WeatherUtil $util
    ): Response {
        $weatherForecasts = $util->getWeatherForCountryAndCity(
            $location->getCountry(),
            $location->getCity()
        );

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'weatherForecasts' => $weatherForecasts,
        ]);
    }
}

