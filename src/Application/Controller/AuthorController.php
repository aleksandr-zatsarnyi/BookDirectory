<?php

namespace App\Application\Controller;

use App\Application\Service\AuthorsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/authors')]
class AuthorController extends AbstractController {

    private AuthorsService $authorService;

    public function __construct(AuthorsService $authorService) {
        $this->authorService = $authorService;
    }

    #[Route('/', name: 'get_all_author', methods: ['GET'])]
    public function getAll(): Response {
        $authors = $this->authorService->getAllAuthors();

        return $this->render('Authors/index.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route('/search', name: 'find_author', methods: ['GET'])]
    public function search(Request $request): Response {
        $searchTerm = $request->query->get('searchTerm');
        $authors = $this->authorService->searchAuthors($searchTerm);

        return $this->render('Authors/search.html.twig', [
            'authors' => $authors,
        ]);
    }
}