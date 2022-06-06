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

    //Ajout + liste des events
    #[Route("/admin/nos-events", name: "app_admin_add_event", methods: ["GET", "POST"])]
    public function event(Request $request, EntityManagerInterface $em): Response{

        //Ajouter un livre
        $event = new event();

        $form = $this->createForm(EventFormType::class, $event);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $event->setParticipants('0');
            
            $em->persist($event);
            $em->flush();

            $this->addFlash('success', 'Votre évènement a été enregistré avec succès !');

            return $this->redirectToRoute('app_admin_dashboard');
        }

        return $this->render('/admin/add/event.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //Modifiez un évènement
    #[Route('/admin/modifiez-l-evenement/{id}', name: 'app_admin_update_event', methods: ['GET', 'POST'])]
    public function updateEvent(Event $event, EntityManagerInterface $em, Request $request): Response
    {
        $form = $this->createForm(EventFormType::class, $event);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $em->persist($event);
            $em->flush();

            $this->addFlash('success', 'L\'évènement a été modifié avec succès !');
            return $this->redirectToRoute('app_admin_dashboard');
        }

        return $this->render('admin/update/event.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
        ]);
    }

    //Supprimez un évènement
    #[Route('/admin/supprimez-l-evenement/{id}', name: 'app_admin_delete_event')]
	public function deleteEvent(Event $event, EventRepository $repository, EntityManagerInterface $em): Response
	{
		$repository->remove($event);
        $em->flush();

        $this->addFlash('error', 'L\'évènement à été supprimé avec succès !');
		return $this->redirectToRoute('app_admin_dashboard');
	}
}