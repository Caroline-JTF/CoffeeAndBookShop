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
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class eventController extends AbstractController
{
    //Voir la fiche de l'evenement
    #[Route("/admin/voir-l-evenement/{id}", name: "app_admin_view_event", methods: ["GET", "POST"])]
    public function viewEvent(Request $request, EventRepository $eventRepository): Response{
        
        $eventUrlArray = explode("/",$request->getUri());
        $eventId = $eventUrlArray[sizeof($eventUrlArray)-1];
        $currentEvent = $eventRepository->find(['id'=>$eventId]);

        return $this->render('/admin/view/event.html.twig', [
            'currentEvent' => $currentEvent,
        ]);
    }

    //Ajout + liste des events
    #[Route("/admin/nos-events", name: "app_admin_add_event", methods: ["GET", "POST"])]
    public function event(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response{

        //Ajouter un livre
        $event = new event();

        $form = $this->createForm(EventFormType::class, $event);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            /** @var UploadedFile $photo */
            $photo = $form->get('img')->getData();

            if($photo) {
                
                $this->handleFile($event, $photo, $slugger);
            }

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
    public function updateEvent(Event $event, EntityManagerInterface $em, Request $request, SluggerInterface $slugger): Response
    {
        $originalPhoto = $event->getImg();

        $form = $this->createForm(EventFormType::class, $event, [
            'img' => $originalPhoto
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('img')->getData();

            if($photo) {
                $this->handleFile($event, $photo, $slugger);
            }
            else if($originalPhoto) {
                $event->setImg($originalPhoto);
            }

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
	public function deleteEvent(Event $event, EventRepository $repository, EntityManagerInterface $em): Response
	{
		$event->setStatus('Annulé');
        $em->flush();

        $this->addFlash('danger', 'Vous avez annulé ' . $event->getName() . ' avec succès !');
		return $this->redirectToRoute('app_admin_dashboard');
	}

    //Remettre un évènement en ligne
    #[Route('/admin/rouvrir-l-evenement/{id}', name: 'app_admin_restore_event')]
    public function restoreEvent(Event $event, EntityManagerInterface $em): Response
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

    //Private function
    private function handleFile(Event $event, UploadedFile $photo, SluggerInterface $slugger): void
    {
        
        $extension = '.' . $photo->guessExtension();

        $safeFilename = $slugger->slug($event->getName());

        $newFilename = $safeFilename . '_' . uniqid() . $extension;

        try {
            $photo->move($this->getParameter('uploads_dir'), $newFilename);
            $event->setImg($newFilename);
        } catch (FileException $exception) {
            $this->addFlash('warning', 'La photo du produit ne s\'est pas importée avec succès. Veuillez réessayer en modifiant le produit.');
        }
    }
}