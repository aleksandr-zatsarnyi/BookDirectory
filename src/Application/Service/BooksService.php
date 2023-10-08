<?php

namespace App\Application\Service;

use App\Application\Domain\Entity\Authors;
use App\Application\Domain\Entity\Books;
use App\Application\Infrastructure\Repository\BooksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        return $this->getBookData($books);
    }

    public function findBookByTitle($title) {
        return $this->booksRepository->findByTitle($title);
    }

    public function create(array $parameters): void {
        $book = new Books($parameters['title'], $parameters['description'], $parameters['imagePath'], $parameters['publicationDate']);
        foreach ($parameters['authors'] as $authorId) {
            $author = $this->getAuthor($authorId);
            $book->addAuthor($author);
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

    private function getAuthor(string $id): ?object {
        return $this->authorsService->getAuthorById($id);;

    }

    public function findAllFiltered(string $term): array {
        $books = $this->booksRepository->findAllFiltered($term);
        return $this->getBookData($books);
    }

    public function update(string $id, array $update) {
        $book = $this->getBookById($id);
        if (!($book instanceof Books)) {
            return "Book with $id is not found";
        }

        if (!empty($update['title'])) {
            $book->setTitle($update['title']);
        }

        if (!empty($update['description'])) {
            $book->setDescription($update['description']);
        }

        if (!empty($update['publicationDate'])) {
            $book->setPublicationDate($update['publicationDate']);
        }

        if (!empty($update['imagePath'])) {
            $book->setImagePath($update['imagePath']);
        }

        if (!empty($update['authors'])) {
            $book->clearAuthors();
            foreach ($update['authors'] as $authorId) {
                $author = $this->getAuthor($authorId);
                if ($author instanceof Authors) {
                    $book->addAuthor($author);
                }
            }
        }

        $this->booksRepository->update($book);
        return "Book with $id is updated";
    }

    public function getBookById($id): ?object {
        return $this->booksRepository->find($id);
    }

    private function getBookData($books): array {
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

    public function uploadImage(UploadedFile $image, string $uploadDirectory): ?string {
        $maxFileSize = 2 * 1024 * 1024;
        $allowedMimeTypes = ['image/jpeg', 'image/png'];

        if ($image->getSize() > $maxFileSize) {
            return null;
        }

        if (!in_array($image->getMimeType(), $allowedMimeTypes)) {
            return null;
        }

        $newFilename = uniqid() . '.' . $image->guessExtension();
        $imagePath = $uploadDirectory . '/' . $newFilename;

        if (file_exists($imagePath)) {
            return null;
        }

        try {
            $image->move(
                $uploadDirectory,
                $newFilename
            );
            return $uploadDirectory . '/' . $newFilename;
        } catch (Exception $e) {
            return null;
        }
    }
}