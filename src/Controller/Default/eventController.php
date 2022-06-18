<?php

declare(strict_types=1);

namespace App\Controller\Default;

use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class eventController extends AbstractController
{
    #[Route("/evenements", name: "app_default_event", methods: ["GET"])]
    public function event(
        EventRepository $eventRepository,
    ): Response
    {
      //Afficher les évènements
      $events = $eventRepository->findAll();

      return $this->render('default/event.html.twig', [
          'events' => $events,
      ]);
    }
}
