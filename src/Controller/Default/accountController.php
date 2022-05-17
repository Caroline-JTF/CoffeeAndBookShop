<?php

namespace App\Controller\Default;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\CommandRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class accountController extends AbstractController
{
    #[Route("/mon-compte", name: "app_default_account", methods: ["GET"])]
    public function showAccount(CommandRepository $repository): Response
    {
        $user = $this->getUser();

        $commands = $repository->findBy(['user' => $this->getUser()]);

        return $this->render('default/account.html.twig', [
            'commands' => $commands,
        ]);
    }
}

?>