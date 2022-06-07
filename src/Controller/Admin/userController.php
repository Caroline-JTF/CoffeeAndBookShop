<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class userController extends AbstractController
{

    //Modifiez un utilisateur
    #[Route('/admin/modifier-l-utilisateur/{id}', name: 'app_admin_update_user', methods: ['GET', 'POST'])]
	public function update(User $user, Request $request, EntityManagerInterface $em): Response
	{
		$form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'L\'utilisateur à été modifié avec succès !');
            return $this->redirectToRoute('app_admin_dashboard');

        }
        return $this->render('/admin/update/user.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
	}

    //Supprimez un utilisateur
    #[Route('/admin/supprimez-l-utilisateur/{id}', name: 'app_admin_delete_user')]
	public function deleteUser(User $user, UserRepository $repository, ReviewRepository $reviewRepository, EntityManagerInterface $em): Response
	{
        $reviews = $reviewRepository->findBy(['user' => $user->getId()]);

        foreach($reviews as $review){
            $review->setUser(Null);
        } 

		$repository->remove($user);
        $em->flush();

        $this->addFlash('error', 'L\'utilisateur à été supprimé avec succès !');
		return $this->redirectToRoute('app_admin_dashboard');
	}
}
