<?php

declare(strict_types=1);

namespace App\Controller\Default;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class homeController extends AbstractController
{
    #[Route("/", name: "app_default_home", methods: ["GET"])]
    public function home(): Response
    {
      return $this->render('default/home.html.twig');

    }

    #[Route("/grain-de-cafe", name: "app_default_coffee-beans", methods: ["GET"])]
    public function coffeeBeans(): Response
    {
      return $this->render('default/coffee-beans.html.twig');

    }
}
