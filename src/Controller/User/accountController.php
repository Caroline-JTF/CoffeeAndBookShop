<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\Participant;
use App\Entity\User;
use App\Entity\Review;
use App\Form\UserFormType;
use App\Repository\EventRepository;
use App\Repository\ParticipantRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class accountController extends AbstractController
{
    // Modifiez les informations 
    #[Route('/mon-compte/modifier-vos-informations/{id}', name: 'app_user_update', methods: ['GET', 'POST'])]
    public function updateUser(
        User $user,
        EntityManagerInterface $em,
        Request $request
    ): Response
    {
    
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $em->persist($user);
            $em->flush();

            $this->addFlash('info', 'Les informations ont été modifié avec succès !');
            return $this->redirectToRoute('app_user_account');

        }
        return $this->render('/user/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Annuler une inscription à un évènement
    #[Route("/mon-compte/supprimer-une-inscription/{id}", name: "app_user_delete_inscription", methods: ['GET'])]
    public function deleteEvent(
        ParticipantRepository $participantRepository,
        EventRepository $eventRepository,
        Participant $participant,
        EntityManagerInterface $em,
    ):Response
    {

        // Récupérer l'id de l'évènement auquel on veut se désinscrire
        $eventId = $participant->getEvent();

        // On récupère l'évènement correspondant à l'ID
        $currentEvent = $eventRepository->find(['id'=>$eventId]);

        // strval : Converti en string
        // On soustrait le nombre de participant inscrit à l'évènement
        $currentEvent->setParticipants( strval($currentEvent->getParticipants() - $participant->getParticipant()));

        // Si l'évènement est complet
        // Ex : Si 50 places pour 50 participants alors 0 place restante
        // Ce cas de figure n'est pas censé arriver
        if ($currentEvent->getPlace() - $currentEvent->getParticipants() == 0){

            // Si l'évènement n'est pas annulé alors le statut passe à complet
            if ($currentEvent->getStatus() !== "Annulé"){
                $currentEvent-> setStatus("Complet");
            }
        }
        // Si l'évènement n'est pas complet et qu'il n'est pas annulé alors le statut passe à ouvert
        else if ($currentEvent->getStatus() !== "Annulé"){
            $currentEvent-> setStatus("Ouvert");
        }

        // On enlève la ligne participant de la table participant
        $participantRepository->remove($participant);

        // On met à jour la BDD
        $em->flush();

        $this->addFlash('success', 'Votre inscription à l\'évènement été supprimée avec succès !');
        return $this->redirectToRoute('app_user_account');
    }

    // Afficher les avis et les évènements
    #[Route("/mon-compte", name: "app_user_account", methods: ["GET"])]
    public function review(
        ReviewRepository $reviewRepository, 
        ParticipantRepository $participantRepository, 
        EventRepository $eventRepository
    ): Response
    {

        // On récupère toutes les reviews écrites par l'utilisateur en cours
        $reviews = $reviewRepository->findBy(['user' => $this->getUser()]);

        // On récupère toutes les lignes de participant ou l'utilisateur est concerné
        $participants = $participantRepository->findBy(['user' => $this->getUser()]);

        // On récupère tous les évènements
        $events = $eventRepository->findAll();

        return $this->render('/user/account.html.twig', [
            'reviews' => $reviews,
            'participants' => $participants,
            'events' => $events,
        ]);
    }

    // Supprimez un avis
    #[Route('/mon-compte/supprimer-un-avis/{id}', name: 'app_user_delete_review')]
	public function deletereview(
        Review $review,
        ReviewRepository $reviewRepository,
        EntityManagerInterface $em
    ): Response
	{
		$reviewRepository->remove($review);
        $em->flush();

        $this->addFlash('danger', 'L\'avis à été supprimé avec succès !');
		return $this->redirectToRoute('app_user_account');
	}
}
