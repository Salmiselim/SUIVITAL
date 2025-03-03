<?php

// src/Repository/ReponseRepository.php

namespace App\Repository;

use App\Entity\Reponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Reponse>
 */
class ReponseRepository extends ServiceEntityRepository
{
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Reponse::class);
        $this->paginator = $paginator;
    }

    public function search($searchTerm, $page = 1, $limit = 10)
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->where('r.commentaire LIKE :searchTerm')  // Adjusted to use 'commentaire'
            ->setParameter('searchTerm', '%'.$searchTerm.'%')
            ->orderBy('r.id', 'DESC');

        $query = $queryBuilder->getQuery();

        return $this->paginator->paginate($query, $page, $limit);
    }
}

