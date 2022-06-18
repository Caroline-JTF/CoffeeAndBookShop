<?php

declare(strict_types=1);

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\RegisterFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class securityController extends AbstractController
{
    //Formulaire d'inscription & conexion :
    #[Route('/inscription', name: 'app_security_sign_up', methods: ['GET', 'POST'])]
    public function inscription(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher,
    ): Response
    {

        // Inscription
        $user = new User();
        $form = $this->createForm(RegisterFormType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(
                $hasher->hashPassword(
                    $user, $form->get('password')->getData()
                )
            );
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_security_login');
        }

        return $this->render('security/sign-up.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //Formulaure de connexion
    #[Route('/connexion', name: 'app_security_login')]
    public function login(
        AuthenticationUtils $authenticationUtils
    ): Response
    {

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 'error' => $error
        ]);
    }

    // Déconnexion
    #[Route(path: '/deconnexion', name: 'app_security_log_out')]
    public function logout(): void
    {
        // throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
