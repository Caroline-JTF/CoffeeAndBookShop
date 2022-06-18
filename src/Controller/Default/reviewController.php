<?php

declare(strict_types=1);

namespace App\Controller\Default;

use DateTime;
use App\Entity\Review;
use App\Form\ReviewFormType;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class reviewController extends AbstractController
{
    #[Route("/les-avis", name: "app_default_review", methods: ["GET", "POST"])]
    public function review(
        Request $request,
        EntityManagerInterface $em,
        ReviewRepository $reviewRepository
    ): Response
    {
        
        //Ajouter un avis
        $review = new review();

        //Remplir automatique le champs si on est connecté
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->getUser();
            $review->setName($user->getFirstname());

            $review->setUser($user);
        }

        $form = $this->createForm(ReviewFormType::class, $review);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $review->setCreatedAt(new DateTime());

            $em->persist($review);
            $em->flush();
            
            return $this->redirectToRoute('app_default_review');
        }

        //Afficher les avis
        $reviews = $reviewRepository->findAll();

        return $this->render('default/review.html.twig', [
            'form' => $form->createView(),
            'reviews' => $reviews,
        ]);

    }
}