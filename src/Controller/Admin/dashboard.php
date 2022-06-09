<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\BookRepository;
use App\Repository\FoodRepository;
use App\Repository\UserRepository;
use App\Repository\CoffeeRepository;
use App\Repository\EventRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class dashboard extends AbstractController
{
    //Route vers le dashboard de l'administateur
    //Afficahage des utilsateurs, cafés, viennoiseries et livres
    #[Route("/admin", name: "app_admin_dashboard", methods: ["GET"])]
    public function Home(
        UserRepository $userRepository, 
        CoffeeRepository $coffeeRepository,
        FoodRepository $foodRepository,
        BookRepository $bookRepository,
        EventRepository $eventRepository,
        PaginatorInterface $paginatorInterface,
        Request $request,
    ): Response
    {
        // Afficher les utilisateurs, boissons, viennoiseries et livres sur le dashboard:
        // Avec un système de pagination pour afficher que 5 élèments par page

        $coffees = $coffeeRepository->findAll();
        $coffees = $paginatorInterface->paginate(
            $coffees,
            $request->query->getInt('page', 1),
            limit: 10
        );

        $foods = $foodRepository->findAll();
        $foods = $paginatorInterface->paginate(
            $foods,
            $request->query->getInt('page', 1),
            limit: 10
        );

        $books = $bookRepository->findAll();
        $books = $paginatorInterface->paginate(
            $books,
            $request->query->getInt('page', 1),
            limit: 10
        );

        $events = $eventRepository->findAll();
        $events = $paginatorInterface->paginate(
            $events,
            $request->query->getInt('page', 1),
            limit: 10
        );
        $users = $userRepository->findAll();
        $users = $paginatorInterface->paginate(
            $users,
            $request->query->getInt('page', 1),
            limit: 10
        );

        return $this->render('/admin/dashboard.html.twig', [
            'coffees' => $coffees,
            'foods' => $foods,
            'books' => $books,
            'events' => $events,
            'users' => $users,
        ]);
    }
    
}
