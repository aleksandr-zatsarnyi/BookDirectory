<?php

namespace App\Application\Controller;

use App\Application\Service\BooksService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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


    #[Route('/search', name: 'search_book', methods: ['GET'])]
    public function search(Request $request): Response {
        $searchTerm = $request->query->get('searchTerm');
        if (empty(trim($searchTerm))) {
            $books = [];
        } else {
            $books = $this->bookService->findAllFiltered($searchTerm);
        }

        return $this->render('Books/search.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/', name: 'create_book', methods: ['POST'])]
    public function create(Request $request): JsonResponse {
        $image = $request->files->get('image');
        if ($image) {
            $imagePath = $this->bookService->uploadImage($image, $this->getParameter('upload_directory'));
        }
        $data = [
            'title' => $request->request->get('title'),
            'description' => $request->request->get('description'),
            'publicationDate' => $request->request->get('publicationDate'),
            'imagePath' => $imagePath ?? '',
            'authors' => json_decode($request->request->get('authors'))
        ];
        if (!$this->validateParam($data)) {
            return new JsonResponse(['message' => 'Authors and Title should be exist'], 400);
        }

        $this->bookService->create($data);

        return new JsonResponse(['message' => 'Book created'], 201);
    }

    #[Route('/{id}', name: 'delete_book', methods: ['DELETE'])]
    public function delete(string $id): JsonResponse {
        $response = $this->bookService->delete($id);
        return new JsonResponse(['message' => $response], 201);
    }


    #[Route('/update/{id}', name: 'update_book', methods: ['POST'])]
    public function update(string $id, Request $request): JsonResponse {
        $image = $request->files->get('image');
        if ($image) {
            $imagePath = $this->bookService->uploadImage($image, $this->getParameter('upload_directory'));
        }
        $data = [
            'title' => $request->request->get('title'),
            'description' => $request->request->get('description'),
            'publicationDate' => $request->request->get('publicationDate'),
            'imagePath' => $imagePath ?? '',
            'authors' => json_decode($request->request->get('authors'))
        ];

        $this->bookService->update($id, $data);

        return new JsonResponse(['message' => 'Book updated'], 200);
    }

    private function validateParam(array $data): bool {
        if (empty($data['title'])) {
            return false;
        }
        if (!is_array($data['authors']) && $data['authors']->isEmpty()) {
            return false;
        }

        return true;
    }
}