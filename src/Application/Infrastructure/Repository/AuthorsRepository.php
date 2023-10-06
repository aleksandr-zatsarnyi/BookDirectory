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

    public function update(int $authorId, array $author): void {
        $author = $this->find($authorId);

        if (!$author) {
            throw new RuntimeException('Author not found');
        }

        if (isset($data['firstName'])) {
            $author->setFirstName($data['firstName']);
        }

        if (isset($data['secondName'])) {
            $author->setFirstName($data['firstName']);
        }

        if (isset($data['lastName'])) {
            $author->setLastName($data['lastName']);
        }

        if (isset($data['books'])) {
            $books = $data['books'];
            foreach ($books as $book) {
                $author->addBook($book);
            }
        }

        $this->_em->flush();
    }

    public function save(Authors $author): void {
        $this->_em->persist($author);
        $this->_em->flush();
    }

    public function delete(Authors $author): void {
        $this->_em->remove($author);
        $this->_em->flush();
    }
}