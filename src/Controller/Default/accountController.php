<?php

namespace App\Controller\Default;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class accountController extends AbstractController
{
    #[Route("/mon-compte", name: "app_default_account", methods: ["GET"])]
    public function account(): Response
    {
        $user = $this->getUser();

        return $this->render('default/account.html.twig');
    }
}

?>