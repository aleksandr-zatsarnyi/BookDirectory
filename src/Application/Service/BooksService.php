<?php

namespace App\Application\Service;

use App\Application\Domain\Entity\Books;
use App\Application\Infrastructure\Repository\BooksRepository;
use Doctrine\ORM\NonUniqueResultException;

class BooksService {
    private BooksRepository $booksRepository;
    private AuthorsService $authorsService;

    public function __construct(BooksRepository $booksRepository, AuthorsService $authorsService) {
        $this->booksRepository = $booksRepository;
        $this->authorsService = $authorsService;
    }

    public function findAllBooks(): array {
        $books = $this->booksRepository->findAll();
        return $books->toArray();
    }

    public function findBookByTitle($title) {
        return $this->booksRepository->findByTitle($title);
    }

    public function create(array $parameters): void {
        $book = new Books($parameters['title'], $parameters['description'], $parameters['imagePath'], $parameters['publicationDate']);
        $author = $this->authorsService->searchAuthors($parameters['authors']);
        if (!$author) {
            $author = $this->authorsService->create();
        }
        $this->booksRepository->save($book);
    }

    public function delete(string $id): string {
        try {
            $book = $this->booksRepository->findById($id);
            if ($book) {
                $this->booksRepository->delete($book);
            } else {
                return "Book with $id is not found";
            }
        } catch (NonUniqueResultException $e) {
            return "Not unique $id";
        }
        return "Book with $id is deleted";
    }
}