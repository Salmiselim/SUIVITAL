<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findLatestPatients(int $limit = 5)
    {
        return $this->createQueryBuilder('u')
            ->where('u INSTANCE OF App\Entity\Patient')
            ->orderBy('u.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getPatientAgeDistribution()
    {
        return $this->createQueryBuilder('u')
            ->select('
                CASE
                    WHEN TIMESTAMPDIFF(YEAR, u.birthdate, CURRENT_DATE()) BETWEEN 0 AND 18 THEN 0
                    WHEN TIMESTAMPDIFF(YEAR, u.birthdate, CURRENT_DATE()) BETWEEN 19 AND 30 THEN 19
                    WHEN TIMESTAMPDIFF(YEAR, u.birthdate, CURRENT_DATE()) BETWEEN 31 AND 45 THEN 31
                    WHEN TIMESTAMPDIFF(YEAR, u.birthdate, CURRENT_DATE()) BETWEEN 46 AND 65 THEN 46
                    ELSE 66
                END as ageRange,
                COUNT(u.id) as count')
            ->where('u INSTANCE OF App\Entity\Patient')
            ->groupBy('ageRange')
            ->getQuery()
            ->getResult();
    }

    public function getRegistrationTrend(int $days = 30)
    {
        return $this->createQueryBuilder('u')
            ->select('DATE(u.createdAt) as date, COUNT(u.id) as count')
            ->where('u.createdAt >= :startDate')
            ->setParameter('startDate', new \DateTime("-{$days} days"))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findTopDoctorsByAppointments(int $limit = 5)
    {
        return $this->createQueryBuilder('u')
            ->select('u.id, u.firstName, u.lastName, COUNT(r.id) as appointmentCount')
            ->join('u.appointments', 'r')
            ->where('u INSTANCE OF App\Entity\Doctor')
            ->groupBy('u.id')
            ->orderBy('appointmentCount', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
