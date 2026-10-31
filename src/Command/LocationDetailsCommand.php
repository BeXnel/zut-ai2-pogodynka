<?php

namespace App\Command;

use App\Repository\LocationRepository;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'location:details',
    description: 'Add a short description for your command',
)]
class LocationDetailsCommand{
    public function __construct(
        private readonly LocationRepository $locationRepository,
    ) {
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Country code')] string $country,
        #[Argument('City name')] string $city,
    ): int {
        /**
         * @var \App\Entity\Location|null $location
         */
        $location = $this->locationRepository->findOneBy([
            'country' => $country,
            'city' => $city,
        ]);

        if ($location === null) {
            $io->error(sprintf('Location with country code "%s" and city "%s" not found.', $country, $city));
            return Command::FAILURE;
        }

        $io->writeln(sprintf('Location: %s, %s', $location->getCity(), $location->getCountry()));
        $io->writeln(sprintf('Coordinates: %f, %f', $location->getLatitude(), $location->getLongitude()));

        return Command::SUCCESS;
    }
}

