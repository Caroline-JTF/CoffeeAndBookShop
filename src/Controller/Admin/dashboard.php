<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\BookRepository;
use App\Repository\FoodRepository;
use App\Repository\UserRepository;
use App\Repository\CoffeeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class dashboard extends AbstractController
{
    //Route vers le dashboard de l'administateur
    //Afficahage des utilsateurs, cafés, viennoiseries et livres
    #[Route("/admin", name: "app_admin_dashboard", methods: ["GET"])]
    public function Home(
        UserRepository $userRepository, 
        CoffeeRepository $coffeeRepository,
        FoodRepository $foodRepository,
        BookRepository $bookRepository
    ): Response
    {
        $users = $userRepository->findAll();
        $coffees = $coffeeRepository->findAll();
        $foods = $foodRepository->findAll();
        $books = $bookRepository->findAll();

        return $this->render('/admin/dashboard.html.twig', [
            'users' => $users,
            'coffees' => $coffees,
            'foods' => $foods,
            'books' => $books,
        ]);
    }
}
