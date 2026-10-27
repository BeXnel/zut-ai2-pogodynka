<?php

namespace App\Controller;

use App\Entity\WeatherForecast;
use App\Form\WeatherForecastType;
use App\Repository\WeatherForecastRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/weather-forecast')]
final class WeatherForecastController extends AbstractController
{
    #[Route(name: 'app_weather_forecast_index', methods: ['GET'])]
    public function index(WeatherForecastRepository $weatherForecastRepository): Response
    {
        return $this->render('weather_forecast/index.html.twig', [
            'weather_forecasts' => $weatherForecastRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_weather_forecast_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): Response {
        $weatherForecast = new WeatherForecast();
        $form = $this->createForm(WeatherForecastType::class, $weatherForecast, [
            'validation_groups' => ['Create'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $errors = $validator->validate($weatherForecast, null, ['Create']);

            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[] = $error->getMessage();
                }
                $this->addFlash('error', implode('<br>', $errorMessages));
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($weatherForecast);
            $entityManager->flush();

            return $this->redirectToRoute('app_weather_forecast_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('weather_forecast/new.html.twig', [
            'weather_forecast' => $weatherForecast,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_weather_forecast_show', methods: ['GET'])]
    public function show(WeatherForecast $weatherForecast): Response
    {
        return $this->render('weather_forecast/show.html.twig', [
            'weather_forecast' => $weatherForecast,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_weather_forecast_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        WeatherForecast $weatherForecast,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): Response {
        $form = $this->createForm(WeatherForecastType::class, $weatherForecast, [
            'validation_groups' => ['Edit'],
        ]);
        $form->handleRequest($request);

         if ($form->isSubmitted()) {
            $errors = $validator->validate($weatherForecast, null, ['Edit']);

            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[] = $error->getMessage();
                }
                $this->addFlash('error', implode('<br>', $errorMessages));
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_weather_forecast_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('weather_forecast/edit.html.twig', [
            'weather_forecast' => $weatherForecast,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_weather_forecast_delete', methods: ['POST'])]
    public function delete(Request $request, WeatherForecast $weatherForecast, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$weatherForecast->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($weatherForecast);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_weather_forecast_index', [], Response::HTTP_SEE_OTHER);
    }
}
