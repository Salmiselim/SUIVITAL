<?php

namespace App\Repository;

use App\Entity\RendezVous;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RendezVous>
 */
class RendezVousRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RendezVous::class);
    }

    //    /**
    //     * @return RendezVous[] Returns an array of RendezVous objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?RendezVous
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function findUpcomingAppointments(int $limit = 5)
    {
        return $this->createQueryBuilder('r')
            ->where('r.date >= :now')
            ->setParameter('now', new \DateTime())
            ->orderBy('r.date', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getWeeklyAppointmentsTrend()
    {
        return $this->createQueryBuilder('r')
            ->select('DAYOFWEEK(r.date) as dayOfWeek, COUNT(r.id) as count')
            ->where('r.date >= :startDate')
            ->setParameter('startDate', new \DateTime('-7 days'))
            ->groupBy('dayOfWeek')
            ->getQuery()
            ->getResult();
    }
}
