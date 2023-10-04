<?php

namespace App\Shared\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


class HealthCheckController extends AbstractController {

    #[Route('/health-check', name: 'health_check', methods: ['GET'])]
    public function check(): JsonResponse {

        return new JsonResponse(['status' => 'ok']);
    }
}