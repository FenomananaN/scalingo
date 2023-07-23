<?php

namespace App\Controller;

use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function home()
    {
        return $this->redirect('/swagger-ui/index.html');
    }
    #[Route('/ping', name: 'app_test', methods: ['GET'])]
    #[OA\Get(path: "/ping")]
    #[OA\Response(
        response: 200,
        description: 'data received when in success',
        content: [
            'application/json' => new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        'message' => new OA\Property(property: 'message', type: 'string'),
                    ]
                )
            )
        ]
    )]
    public function ping(): JsonResponse
    {
        return $this->json(
            ["message" => "pong"],
            401
        );
    }
}
