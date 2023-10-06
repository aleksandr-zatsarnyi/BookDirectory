<?php

namespace App\Application\Service;

use App\Application\Domain\Entity\Authors;
use App\Application\Infrastructure\Repository\AuthorsRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use RuntimeException;

class AuthorsService {
    private AuthorsRepository $authorsRepository;

    public function __construct(AuthorsRepository $authorRepository) {
        $this->authorsRepository = $authorRepository;
    }

    public function getAllAuthors(int $page, int $perPage): array {
        $results = $this->authorsRepository->findAllSortedByLastName($page, $perPage);
        $data = [];
        foreach ($results as $result) {
            $data[] = [
                'id' => $result->getId(),
                'firstName' => $result->getFirstName(),
                'secondName' => $result->getSecondName(),
                'lastName' => $result->getLastName()
            ];
        }
        return $data;
    }

    public function searchAuthors($searchTerm): array {
        return $this->authorsRepository->searchAuthors($searchTerm);
    }

    public function getAuthorById($authorId) {
        return $this->authorsRepository->find($authorId);
    }

    public function create(array $parameters): Authors {
        $author = new Authors($parameters['firstName'], $parameters['secondName'], $parameters['lastName']);
        $this->authorsRepository->save($author);

        return $author;
    }

    public function update(int $id, array $parameters) {
        try {
            $this->authorsRepository->update($id, $parameters);
        } catch (RuntimeException $e) {
            return $e->getMessage();
        }
        return "Author with $id is updated";
    }


    public function delete(string $id): string {
        try {
            $author = $this->authorsRepository->findById($id);
            if ($author) {
                $this->authorsRepository->delete($author);
            } else {
                return "Author with $id is not found";
            }
        } catch (NonUniqueResultException $e) {
            return "Not unique $id";
        }
        return "Author with $id is deleted";
    }
}