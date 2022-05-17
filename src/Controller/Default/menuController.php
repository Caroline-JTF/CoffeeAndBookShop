<?php

declare(strict_types=1);

namespace App\Controller\Default;

use App\Repository\BookRepository;
use App\Repository\FoodRepository;
use App\Repository\CoffeeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class menuController extends AbstractController
{
    #[Route("/la-carte", name: "app_menu", methods: ["GET"])]
    public function menu(
        CoffeeRepository $coffeeRepository,
        FoodRepository $foodRepository,
        BookRepository $bookRepository
    ): Response{

        $coffees = $coffeeRepository->findAll();
        $foods = $foodRepository->findAll();
        $books = $bookRepository->findAll();

        return $this->render('/default/menu.html.twig', [
            'coffees' => $coffees,
            'foods' => $foods,
            'books' => $books,
        ]);
    }
}