<?php

namespace App\Application\Infrastructure\Repository;

use App\Application\Domain\Entity\Books;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

class BooksRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Books::class);
    }

    public function findByTitle(string $title) {
        return $this->createQueryBuilder('b')
            ->where('b.title LIKE :title')
            ->setParameter('title', '%' . $title . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findById(string $id) {
        return $this->createQueryBuilder('b')
            ->where('b.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAll($page = 1, $perPage = 15): Paginator {
        $query = $this->createQueryBuilder('b')
            ->orderBy('b.title', 'ASC')
            ->getQuery();

        $paginator = new Paginator($query);
        $paginator
            ->getQuery()
            ->setFirstResult($perPage * ($page - 1))
            ->setMaxResults($perPage);
        return $paginator;
    }

    public function findAllFiltered(string $term): ArrayCollection {
        $qb = $this->createQueryBuilder('b')
            ->orderBy('b.title', 'ASC');

        $qb->leftJoin('b.authors', 'a')
            ->where('b.title LIKE :term OR a.firstName LIKE :term OR a.lastName LIKE :term OR CONCAT(a.firstName, \' \', a.lastName) LIKE :term')
            ->setParameter('term', '%' . $term . '%');


        $results = $qb->getQuery()->getResult();

        return new ArrayCollection($results);
    }

    public function save(Books $book): void {
        $this->_em->persist($book);
        $this->_em->flush();
    }

    public function delete(Books $book): void {
        $this->_em->remove($book);
        $this->_em->flush();
    }

    public function update(Books $book): void {
        $this->_em->persist($book);
        $this->_em->flush();
    }
}