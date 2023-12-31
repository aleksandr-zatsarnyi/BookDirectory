<?php

namespace App\Application\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class WelcomeController extends AbstractController {

    #[Route('/welcome', name: 'welcome_index', methods: ['GET'])]
    public function index(): Response {
        return $this->render('Welcome/index.html.twig');
    }
}