<?php

namespace App\Application\Service;

use App\Application\Domain\Entity\Authors;
use App\Application\Infrastructure\Repository\AuthorsRepository;

class AuthorsService {
    private AuthorsRepository $authorsRepository;

    public function __construct(AuthorsRepository $authorRepository) {
        $this->authorsRepository = $authorRepository;
    }

    public function getAllAuthors() {
        return $this->authorsRepository->findAllSortedByLastName();
    }

    public function searchAuthors($searchTerm) {
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
}