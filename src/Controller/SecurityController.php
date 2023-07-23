<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(): JsonResponse
    {
        return $this->json([
            'messjhjage' => $this->getUser() ? $this->getUser()->getId() : null,
        ]);
    }
    #[Route('/api/loggin_check', name: 'app_login_app')]
    public function logincheck(): JsonResponse
    {
        return $this->json([
            'logincheck' => $this->getUser() ? $this->getUser()->getId() : null,
        ]);
    }
    #[Route('/logout', name: 'app_logout')]
    public function logout(): JsonResponse
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
