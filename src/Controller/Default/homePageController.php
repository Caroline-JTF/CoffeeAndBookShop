<?php

declare(strict_types=1);

namespace App\Controller\Default;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class homePageController extends AbstractController
{
    #[Route("/", name: "app_default_home_page", methods: ["GET"])]
    public function homepage(): Response
    {
      return $this->render('default/home-page.html.twig');

    }
}
