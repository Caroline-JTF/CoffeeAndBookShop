<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class eventController extends AbstractController
{
    //Voir la fiche de l'evenement
    #[Route("/voir-l-evenement/{id}", name: "app_user_view_event", methods: ["GET", "POST"])]
    public function viewEvent(
        Request $request,
        EventRepository $eventRepository,
    ): Response{

        $eventUrlArray = explode("/",$request->getUri());
        $eventId = $eventUrlArray[sizeof($eventUrlArray)-1];
        $currentEvent = $eventRepository->find(['id'=>$eventId]);

        return $this->render('/user/view-event.html.twig', [
            'currentEvent' => $currentEvent,
        ]);
    }
}