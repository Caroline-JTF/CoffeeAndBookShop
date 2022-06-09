<?php

declare(strict_types=1);

namespace App\Controller\User;

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
    public function updateUser(User $user, EntityManagerInterface $em, Request $request): Response
    {
    
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Les informations ont été modifié avec succès !');
            return $this->redirectToRoute('app_user_account');

        }
        return $this->render('/user/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Afficher les avis et les évènements
    #[Route("/mon-compte", name: "app_user_account", methods: ["GET"])]
    public function review(
        ReviewRepository $reviewRepository, 
        ParticipantRepository $participantRepository, 
        EventRepository $eventRepository
        ): Response
    {
        // $user = $userRepository->findBy(['id' => $this->getUser()], ['id' => 'ASC'],1,0);
        // $reviews = $user[0]->getReviews();
        $reviews = $reviewRepository->findBy(['user' => $this->getUser()]);
        $participants = $participantRepository->findBy(['user' => $this->getUser()]);
        $events = $eventRepository->findAll();

        return $this->render('/user/account.html.twig', [
            'reviews' => $reviews,
            'participants' => $participants,
            'events' => $events,
        ]);
    }

    // Supprimez un avis
    #[Route('/mon-compte/supprimer-un-avis/{id}', name: 'app_user_delete_review')]
	public function deletereview(Review $review, ReviewRepository $reviewRepository, EntityManagerInterface $em): Response
	{
		$reviewRepository->remove($review);
        $em->flush();

        $this->addFlash('error', 'L\'avis à été supprimé avec succès !');
		return $this->redirectToRoute('app_user_account');
	}
}
