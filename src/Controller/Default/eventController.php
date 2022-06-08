<?php

declare(strict_types=1);

namespace App\Controller\Default;

use App\Repository\EventRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class eventController extends AbstractController
{
    #[Route("/evenements", name: "app_default_event", methods: ["GET"])]
    public function event(EventRepository $eventRepository, PaginatorInterface $paginatorInterface, Request $request): Response
    {
      //Afficher les évènements
      $events = $eventRepository->findAll();
        // $events = $paginatorInterface->paginate(
        //     $events,
        //     $request->query->getInt('page', 1),
        //     limit: 5
        // );

      return $this->render('default/event.html.twig', [
          'events' => $events,
      ]);
    }
}
