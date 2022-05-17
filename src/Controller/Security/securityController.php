<?php

declare(strict_types=1);

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class securityController extends AbstractController
{
    //Formulaire d'inscription :
    #[Route('/signUp', name: 'app_security_signUp', methods: ['GET', 'POST'])]
    public function inscription(Request $request, EntityManagerInterface $entity, UserPasswordHasherInterface $hasher): Response 
    {
        $user = new User();
        $form = $this->createForm(UserFormType::class, $user)
                     ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(
                $hasher->hashPassword(
                    $user, $form->get('password')->getData()
                )
            );
            $entity->persist($user);
            $entity->flush();

            $this->addFlash('success', 'Votre inscription est validée. Connectez-vous à présent !');
            return $this->redirectToRoute('app_default_home');
        }

        return $this->render('security/signUp.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //Formulaure de connexion
    #[Route('/login', name: 'app_security_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 'error' => $error
        ]);
    }

    // Déconnexion
    #[Route(path: '/deconnexion', name: 'app_security_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
