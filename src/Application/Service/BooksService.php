<?php

namespace App\Application\Service;

use App\Application\Domain\Entity\Authors;
use App\Application\Domain\Entity\Books;
use App\Application\Infrastructure\Repository\BooksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class BooksService {
    private BooksRepository $booksRepository;
    private AuthorsService $authorsService;

    public function __construct(BooksRepository $booksRepository, AuthorsService $authorsService) {
        $this->booksRepository = $booksRepository;
        $this->authorsService = $authorsService;
    }

    public function findAllBooks(int $page, int $perPage): array {
        $books = $this->booksRepository->findAll($page, $perPage);
        $bookData = [];
        foreach ($books as $book) {
            $authorData = [];
            foreach ($book->getAuthors() as $author) {
                $authorData[] = [
                    'id' => $author->getId(),
                    'firstName' => $author->getFirstName(),
                    'secondName' => $author->getSecondName(),
                    'lastName' => $author->getLastName(),
                ];
            }
            $bookData[] = [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'description' => $book->getDescription(),
                'imagePath' => $book->getImagePath(),
                'publicationDate' => $book->getPublicationDate(),
                'authors' => $authorData
            ];
        }
        return $bookData;
    }

    public function findBookByTitle($title) {
        return $this->booksRepository->findByTitle($title);
    }

    public function create(array $parameters): void {
        $book = new Books($parameters['title'], $parameters['description'], $parameters['imagePath'], $parameters['publicationDate']);
        //$author = $this->getAuthor($parameters['authors']);
        //$book->addAuthor($author);
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

    private function getAuthor(array $authorData): Authors {
        $author = $this->authorsService->searchAuthors($authorData['lastName']);
        if (!$author) {
            $author = $this->authorsService->create($authorData);
        } else {
            $author = $author[0];
        }
        return $author;
    }

    public function findAllFiltered(string $term) {
        return $this->booksRepository->findAllFiltered($term);
    }
}