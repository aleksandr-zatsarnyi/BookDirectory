<?php

namespace App\Application\Infrastructure\Repository;

use App\Application\Domain\Entity\Authors;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AuthorsRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Authors::class);
    }

    public function findAllSortedByLastName() {
        return $this->createQueryBuilder('a')
            ->orderBy('a.lastName', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function searchAuthors($searchTerm) {
        return $this->createQueryBuilder('a')
            ->where('a.firstName LIKE :searchTerm OR a.lastName LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();
    }

    public function findAuthorsByIds(array $authorIds) {
        return $this->createQueryBuilder('a')
            ->where('a.id IN (:authorIds)')
            ->setParameter('authorIds', $authorIds)
            ->getQuery()
            ->getResult();
    }
}