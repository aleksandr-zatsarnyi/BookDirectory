<?php

namespace App\Application\Controller;

use App\Application\Domain\Entity\Books;
use App\Application\Service\BooksService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController {

    private BooksService $bookService;

    public function __construct(BooksService $bookService) {
        $this->bookService = $bookService;
    }

    #[Route('/api/books', name: 'get_all_books', methods: ['GET'])]
    public function getAll(): JsonResponse {
        $books = $this->bookService->findAllBooks();
        $bookData = [];
        foreach ($books as $book) {
            $bookData[] = [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'description' => $book->getDescription(),
                'imagePath' => $book->getImagePath()
            ];
        }
        $jsonContent = json_encode($bookData);
        return new JsonResponse($jsonContent);
    }


    #[Route('/api/books/{title}', name: 'find_books_by_title', methods: ['GET'])]
    public function findBooksByTitle(string $title): JsonResponse {
        $books = $this->bookService->findBookByTitle($title);
        if ($books) {
            $bookData = [];
            foreach ($books as $book) {
                $bookData[] = [
                    'id' => $book->getId(),
                    'title' => $book->getTitle(),
                    'description' => $book->getDescription(),
                    'imagePath' => $book->getImagePath()
                ];
            }
            $jsonContent = json_encode($bookData);
            return new JsonResponse($jsonContent);
        } else {
            return new JsonResponse(['error' => 'Book not found'], 404);
        }
    }

    #[Route('/api/books', name: 'create_book', methods: ['POST'])]
    public function create(Request $request): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $this->bookService->create($data);

        return new JsonResponse(['message' => 'Book created'], 201);
    }

    #[Route('/api/books/{id}', name: 'delete_book', methods: ['DELETE'])]
    public function delete(string $id): JsonResponse {
        $response = $this->bookService->delete($id);
        return new JsonResponse(['message' => $response], 201);
    }
}