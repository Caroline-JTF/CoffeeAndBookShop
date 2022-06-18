<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Form\EventFormType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class eventController extends AbstractController
{
    //Voir la fiche de l'evenement
    #[Route("/admin/voir-l-evenement/{id}", name: "app_admin_view_event", methods: ["GET", "POST"])]
    public function viewEvent(
        Request $request,
        EventRepository $eventRepository
    ): Response{
        
        $eventUrlArray = explode("/",$request->getUri());
        $eventId = $eventUrlArray[sizeof($eventUrlArray)-1];
        $currentEvent = $eventRepository->find(['id'=>$eventId]);

        return $this->render('/admin/view/event.html.twig', [
            'currentEvent' => $currentEvent,
        ]);
    }

    //Ajout + liste des events
    #[Route("/admin/nos-events", name: "app_admin_add_event", methods: ["GET", "POST"])]
    public function event(
        Request $request,
        EntityManagerInterface $em,
    ): Response{

        //Ajouter un livre
        $event = new event();

        $form = $this->createForm(EventFormType::class, $event);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $event->setParticipants('0');
            $event->setStatus('Ouvert');
            
            $em->persist($event);
            $em->flush();

            $this->addFlash('success', 'Vous avez ajouté ' . $event->getName() . ' avec succès !');
            return $this->redirectToRoute('app_admin_dashboard');
        }

        return $this->render('/admin/add/event.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //Modifiez un évènement
    #[Route('/admin/modifiez-l-evenement/{id}', name: 'app_admin_update_event', methods: ['GET', 'POST'])]
    public function updateEvent(
        Event $event,
        EntityManagerInterface $em,
        Request $request,
    ): Response
    {
        $form = $this->createForm(EventFormType::class, $event);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $em->persist($event);
            $em->flush();

            $this->addFlash('info', 'Vous avez modifié ' . $event->getName() . ' avec succès !');
            return $this->redirectToRoute('app_admin_dashboard');
        }

        return $this->render('admin/update/event.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
        ]);
    }

    //Supprimez un évènement
    #[Route('/admin/supprimez-l-evenement/{id}', name: 'app_admin_delete_event')]
	public function deleteEvent(
        Event $event,
        EventRepository $repository,
        EntityManagerInterface $em
    ): Response
	{
		$event->setStatus('Annulé');
        $em->flush();

        $this->addFlash('danger', 'Vous avez annulé ' . $event->getName() . ' avec succès !');
		return $this->redirectToRoute('app_admin_dashboard');
	}

    //Remettre un évènement en ligne
    #[Route('/admin/rouvrir-l-evenement/{id}', name: 'app_admin_restore_event')]
    public function restoreEvent(
        Event $event,
        EntityManagerInterface $em
    ): Response
    {
        if($event->getPlace()-$event->getParticipants() == 0){
            $event->setStatus('Complet');
        } else {
            $event->setStatus('Ouvert');
        }

        $em->flush();

        $this->addFlash('success', 'Vous avez réouvert ' . $event->getName() . ' avec succès !');
        return $this->redirectToRoute('app_admin_dashboard');
    }
}