<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\WeatherForecastRepository;
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
        WeatherForecastRepository $repository
    ): Response {
        $weatherForecasts = $repository->findByLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'weatherForecasts' => $weatherForecasts,
        ]);
    }
}

