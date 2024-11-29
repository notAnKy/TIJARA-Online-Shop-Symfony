<?php
// src/Repository/InternauteRepository.php

namespace App\Repository;

use App\Entity\Internaute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class InternauteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Internaute::class);
    }

    public function findBySearchQuery(string $searchQuery): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.login LIKE :searchQuery')
            ->setParameter('searchQuery', '%' . $searchQuery . '%')
            ->getQuery()
            ->getResult();
    }
}
