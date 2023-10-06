<?php

namespace App\Application\Infrastructure\Repository;

use App\Application\Domain\Entity\Authors;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

class AuthorsRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Authors::class);
    }

    public function findAllSortedByLastName($page = 1, $perPage = 15): Paginator {
        $query = $this->createQueryBuilder('a')
            ->orderBy('a.lastName', 'ASC')
            ->getQuery();
        $paginator = new Paginator($query);
        $paginator
            ->getQuery()
            ->setFirstResult($perPage * ($page - 1))
            ->setMaxResults($perPage);

        return $paginator;
    }

    public function searchAuthors(string $searchTerm): array {
        return $this->createQueryBuilder('a')
            ->where('CONCAT(a.firstName, \' \', a.lastName) LIKE :searchTerm')
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
    /**
     * @throws NonUniqueResultException
     */
    public function findById(string $id) {
        return $this->createQueryBuilder('a')
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function update(string $authorId, array $update): void {
        $author = $this->find($authorId);

        if (!$author) {
            throw new RuntimeException('Author not found');
        }

        if (!empty($update['firstName'])) {
            $author->setFirstName($update['firstName']);
        }

        if (isset($update['secondName'])) {
            $author->setSecondName($update['secondName']);
        }

        if (!empty($update['lastName'])) {
            $author->setLastName($update['lastName']);
        }

        $this->_em->flush();
    }

    public function save(Authors $author): void {
        $this->_em->persist($author);
        $this->_em->flush();
    }

    public function delete(string $id): void {
        $author = $this->find($id);
        if (!$author) {
            throw new RuntimeException('Author not found');
        }
        $this->_em->remove($author);
        $this->_em->flush();
    }
}