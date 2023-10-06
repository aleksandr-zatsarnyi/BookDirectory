<?php

namespace App\Application\Controller;

use App\Application\Service\BooksService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/books')]
class BookController extends AbstractController {

    private BooksService $bookService;

    public function __construct(BooksService $bookService) {
        $this->bookService = $bookService;
    }

    #[Route('/', name: 'books_index', methods: ['GET'])]
    public function index() {
        return $this->render('Books/index.html.twig');
    }

    #[Route('/get', name: 'get_all_books', methods: ['GET'])]
    public function getAll(PaginatorInterface $paginator, Request $request): JsonResponse {
        $page = $request->query->getInt('page', 1);
        $books = $this->bookService->findAllBooks($page, 15);

        return new JsonResponse($books);
    }


    #[Route('/{term}', name: 'search_book', methods: ['GET'])]
    public function findBooksByTitle(string $term): JsonResponse {
        $books = $this->bookService->findAllFiltered($term);
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

    #[Route('/', name: 'create_book', methods: ['POST'])]
    public function create(Request $request): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $this->bookService->create($data);

        return new JsonResponse(['message' => 'Book created'], 201);
    }

    #[Route('/{id}', name: 'delete_book', methods: ['DELETE'])]
    public function delete(string $id): JsonResponse {
        $response = $this->bookService->delete($id);
        return new JsonResponse(['message' => $response], 201);
    }
}