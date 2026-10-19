<?php

namespace App\Repository;

use App\Entity\Location;
use App\Entity\WeatherForecast;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WeatherForecast>
 */
class WeatherForecastRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WeatherForecast::class);
    }

    public function findByLocation(Location $location)
    {
        $qb = $this->createQueryBuilder('wf');
        $qb->where('wf.location = :location')
            ->setParameter('location', $location)
            ->andWhere('wf.date > :now')
            ->setParameter('now', date('Y-m-d'));

        $query = $qb->getQuery();
        return $query->getResult();
    }
}
