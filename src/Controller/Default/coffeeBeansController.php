<?php

declare(strict_types=1);

namespace App\Controller\Default;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class coffeeBeansController extends AbstractController
{
    #[Route("/grain-de-cafe", name: "app_default_coffee_beans", methods: ["GET"])]
    public function coffeeBeans(): Response
    {
      return $this->render('default/coffee-beans.html.twig');

    }
}
