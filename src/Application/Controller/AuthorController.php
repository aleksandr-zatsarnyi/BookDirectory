<?php

namespace App\Application\Controller;

use App\Application\Domain\Dto\ActorDTO;
use App\Application\Service\AuthorsService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/authors')]
class AuthorController extends AbstractController {

    private AuthorsService $authorService;

    public function __construct(AuthorsService $authorService) {
        $this->authorService = $authorService;
    }

    #[Route('/', name: 'authors_index', methods: ['GET'])]
    public function index(): Response {

        return $this->render('Authors/index.html.twig');
    }

    #[Route('/get', name: 'get_all_author', methods: ['GET'])]
    public function getAll(PaginatorInterface $paginator, Request $request): Response {
        $page = $request->query->getInt('page', 1);
        $response = $this->authorService->getAllAuthors($page, 15);

        return new JsonResponse($response);
    }

    #[Route('/search', name: 'find_author', methods: ['GET'])]
    public function search(Request $request): Response {
        $searchTerm = $request->query->get('searchTerm');
        if (empty(trim($searchTerm))) {
            $authors = [];
        } else {
            $authors = $this->authorService->searchAuthors($searchTerm);
        }

        return $this->render('Authors/search.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route('/', name: 'create_author', methods: ['POST'])]
    public function create(Request $request): JsonResponse {
        $data = json_decode($request->getContent(), true);
        if (!$this->validateParam($data)) {
            return new JsonResponse(['message' => 'name and lastname should be exist'], 400);
        }
        $this->authorService->create($data);

        return new JsonResponse(['message' => 'Book created'], 201);
    }

    #[Route('/{id}', name: 'update_author', methods: ['PUT'])]
    public function update(Request $request, string $id): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $response = $this->authorService->update($id, $data);

        return new JsonResponse(['message' => $response], 200);
    }

    #[Route('/{id}', name: 'delete_author', methods: ['DELETE'])]
    public function delete(string $id): JsonResponse {
        $response =  $this->authorService->delete($id);

        return new JsonResponse(['message' => $response], 200);
    }

    private function validateParam(array $data): bool {
        if (empty($data['firstName'])) {
           return false;
        }
        if (empty($data['lastName']) && strlen($data['lastName']) < 3) {
            return false;
        }
        return true;
    }
}