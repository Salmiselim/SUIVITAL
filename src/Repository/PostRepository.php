<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findAllQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.created_at', 'DESC');  // Trier les posts par date de création
    }

    // Méthode pour effectuer une recherche par titre ou contenu de post
    public function searchQuery(string $query): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->where('p.title LIKE :query OR p.content LIKE :query')
            ->setParameter('query', '%'.$query.'%')
            ->orderBy('p.created_at', 'DESC');
    }

    /*public function search(?string $query): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.title LIKE :query')
            ->orWhere('p.content LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }*/
    
    //    /**
    //     * @return Post[] Returns an array of Post objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Post
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
