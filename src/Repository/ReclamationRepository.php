<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface; // Correct namespace

/**
 * @extends ServiceEntityRepository<Reclamation>
 */
class ReclamationRepository extends ServiceEntityRepository
{
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Reclamation::class);
        $this->paginator = $paginator;
    }

    public function search($searchTerm, $page = 1, $limit = 10)
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->where('r.objet LIKE :searchTerm')
            ->orWhere('r.commentaire LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$searchTerm.'%')
            ->orderBy('r.id', 'DESC');

        $query = $queryBuilder->getQuery();

        return $this->paginator->paginate($query, $page, $limit);
    }
}
