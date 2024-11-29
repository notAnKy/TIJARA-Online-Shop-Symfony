<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    // Custom method to find articles based on search query
    public function findBySearchQuery(string $searchQuery): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.libArt LIKE :searchQuery')
            ->setParameter('searchQuery', '%'.$searchQuery.'%')
            ->getQuery()
            ->getResult();
    }
}
