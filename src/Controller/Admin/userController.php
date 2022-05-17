<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class userController extends AbstractController
{

    //Modifiez un utilisateur
    #[Route('/admin/modifiez-l-utilisateur/{id}', name: 'app_admin_update_user', methods: ['GET', 'POST'])]
    public function updateUser(User $user, EntityManagerInterface $entityManager, Request $request): Response
    {

        $form = $this->createForm(UserFormType::class, $user)
                     ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Vous avez modifié l\'utilisateur avec succès !');
            return $this->redirectToRoute('app_admin_dashboard');
        }

        return $this->render('admin/update/user.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    //Supprimez un utilisateur
    #[Route('/admin/supprimez-l-utilisateur/{id}', name: 'app_admin_delete_user')]
	public function deleteUser(User $user, UserRepository $repository): Response
	{
		$repository->remove($user);

		return $this->redirectToRoute('app_admin_dashboard');
	}
}
