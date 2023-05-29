<?php

namespace App\Controller;

use App\Entity\Human;
use App\Repository\HumanRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HumanController extends AbstractController
{
    #[Route(
        name: '_api_/humans{._format}_get_collection',
        path: '/api/humans',
    )]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $humans = $entityManager->getRepository(Human::class)->paginateByRequest($request);

        return $this->json([
            'humans' => $humans,
            'metadata' => [
                'per_page' => max($request->query->getInt('per_page', HumanRepository::PER_PAGE), 1),
            ],
            'links' => [],
        ]);
    }
}
