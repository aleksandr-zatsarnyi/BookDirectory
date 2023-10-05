<?php

namespace App\Application\Service;

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
}