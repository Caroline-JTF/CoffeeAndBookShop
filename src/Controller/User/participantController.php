<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\Participant;
use App\Form\ParticipationFormType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class participantController extends AbstractController
{
    #[Route("/mon-compte/formulaire-evenement/{id}", name: "app_user_participant", methods: ["GET", "POST"])]
    public function participant(
        Request $request,
        EntityManagerInterface $em,
        EventRepository $eventRepository
    ): Response{
        
        // ========== FORMULAIRE ==========
        
        $participant = new Participant();
        
        // Permet de récupérer le nom et le prénom de l'utilisateur connecté
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->getUser();
            $participant->setFirstname($user->getFirstname());
            $participant->setLastname($user->getLastname());
        }

        $form = $this->createForm(ParticipationFormType::class, $participant);
        $form->handleRequest($request);

        // Récupération de l'URL actuelle 
        // Explode : Sépare l'url en une liste
        $eventUrlArray = explode("/",$request->getUri());
        
        // Récupération de l'élément en dernière position, ici l'id
        $eventId = $eventUrlArray[sizeof($eventUrlArray)-1];
        
        // Permet de récupérer l'évènement actuel
        $currentEvent = $eventRepository->find(['id'=>$eventId]);

        if($form->isSubmitted() && $form->isValid()){

            // Erreur si on essaie d'inscrire plus de personne que de place disponible
            $dispo = $currentEvent->getPlace() - $currentEvent->getParticipants();

            if ($form['participant']->getData() > $dispo){
                $this->addFlash('error', 'Il n\'y plus que ' . strval($dispo) . ' place(s) disponible pour cet évènement');
                return $this->redirectToRoute('app_default_event');
            }
            
            else {
                // Récupération de l'utilisateur
                $participant->setUser($this->getUser());

                // Récupération de l'évènement
                $participant->setEvent($eventRepository->find(['id' => $eventId]));

                // Incrémentation du nombre de participant
                $eventRepository->find(['id'=>$eventId])->setParticipants(strval($eventRepository->find(['id'=>$eventId])->getParticipants()+$form['participant']->getData()));

                // On récupère l'évènement sur lequel on se trouve
                // Et s'il y a autant de participant que de place le statut passera à complet
                $currentEvent = $eventRepository->find(['id'=>$eventId]);
                if($currentEvent->getPlace() - $currentEvent->getParticipants() == 0) {
                    $eventRepository->find(['id' => $eventId])->setStatus('Complet');
                }

                $em->persist($participant);
                $em->flush();

                $this->addFlash('success', 'Votre inscription a été enregistrée avec succès !');
                return $this->redirectToRoute('app_default_event');
            }
        }

        return $this->render('/user/participant.html.twig', [
            'form' => $form->createView(),
            'currentEvent' => $currentEvent,
        ]);
    }
}
