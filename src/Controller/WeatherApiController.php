<?php

namespace App\Controller;

use App\Entity\WeatherForecast;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api')]
    public function index(
         WeatherUtil $util,
         #[MapQueryParameter] string $city,
         #[MapQueryParameter] string $country,
         #[MapQueryParameter] string $format,
         #[MapQueryParameter('twig')] bool $twig = false,
    ): Response
    {
        $weatherForecasts = $util->getWeatherForCountryAndCity($country, $city);

        if ($twig) {
            return $this->render("weather_api/index.{$format}.twig", [
                'city' => $city,
                'country' => $country,
                'weatherForecasts' => $weatherForecasts,
            ], new Response('', Response::HTTP_OK, [
                'Content-Type' => $format === 'csv' ? 'text/csv' : 'application/json',
            ]));
        }

        if ($format === 'csv') {
            $lines = array_map(
                fn(WeatherForecast $forecast) => sprintf(
                    '%s,%s,%s,%s,%s',
                    $city,
                    $country,
                    $forecast->getDate()->format('Y-m-d'),
                    $forecast->getTemperature(),
                    $forecast->getFahrenheit()
                ),
                $weatherForecasts
            );

            $csv = "city,country,date,celsius,fahrenheit\n" . implode("\n", $lines);

            return new Response($csv, Response::HTTP_OK, [
                'Content-Type' => 'text/csv',
            ]);
        }

        return $this->json([
            'city' => $city,
            'country' => $country,
            'weatherForecasts' => array_map(fn(WeatherForecast $forecast) => [
                'date' => $forecast->getDate()->format('Y-m-d'),
                'celsius' => $forecast->getTemperature(),
                'fahrenheit' => $forecast->getFahrenheit(),
            ], $weatherForecasts),
        ]);
    }
}
