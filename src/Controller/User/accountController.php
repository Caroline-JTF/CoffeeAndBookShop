<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class accountController extends AbstractController
{
    #[Route("/mon-compte", name: "app_user_account", methods: ["GET"])]
    public function account(UserRepository $userRepository): Response
    {
        return $this->render('/user/account.html.twig');
    }
}
