<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Coffee;
use App\Form\CoffeeFormType;
use App\Repository\CoffeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class coffeeController extends AbstractController
{

    //Ajout d'un café
    #[Route("/admin/ajoutez-un-cafe", name: "app_admin_add_coffee", methods: ["GET", "POST"])]
    public function coffee(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response{

        //Ajouter un café
        $coffee = new coffee();

        $form = $this->createForm(CoffeeFormType::class, $coffee);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            /** @var UploadedFile $photo */
            $photo = $form->get('img')->getData();

            if($photo) {
                
                $this->handleFile($coffee, $photo, $slugger);
            }
            
            $em->persist($coffee);
            $em->flush();

            $this->addFlash('success', 'La boisson a été modifié avec succès !');
            return $this->redirectToRoute('app_admin_dashboard');

        }

        return $this->render('/admin/add/coffee.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //Modifiez un café
    #[Route('/admin/modifiez-le-cafe/{id}', name: 'app_admin_update_coffee', methods: ['GET', 'POST'])]
    public function updateCoffee(Coffee $coffee, EntityManagerInterface $em, Request $request, SluggerInterface $slugger): Response
    {
        $originalPhoto = $coffee->getImg();

        $form = $this->createForm(CoffeeFormType::class, $coffee, [
            'img' => $originalPhoto
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('img')->getData();

            if($photo) {
                $this->handleFile($coffee, $photo, $slugger);
            }
            else {
                $coffee->setImg($originalPhoto);
            }

            $em->persist($coffee);
            $em->flush();

            $this->addFlash('success', 'Vous avez modifié le produit avec succès !');
            return $this->redirectToRoute('app_admin_dashboard');
        }

        return $this->render('admin/update/coffee.html.twig', [
            'form' => $form->createView(),
            'coffee' => $coffee,
        ]);
    }

    //Supprimez un café
    #[Route('/admin/supprimez-le-cafe/{id}', name: 'app_admin_delete_coffee')]
	public function deleteCoffee(Coffee $coffee, CoffeeRepository $repository, EntityManagerInterface $em): Response
	{
		$repository->remove($coffee);
        $em->flush();

        $this->addFlash('error', 'La boisson à été supprimé avec succès !');
		return $this->redirectToRoute('app_admin_dashboard');
	}

    //Private function
    private function handleFile(Coffee $coffee, UploadedFile $photo, SluggerInterface $slugger): void
    {
        
        $extension = '.' . $photo->guessExtension();

        $safeFilename = $slugger->slug($coffee->getName());

        $newFilename = $safeFilename . '_' . uniqid() . $extension;

        try {
            $photo->move($this->getParameter('uploads_dir'), $newFilename);
            $coffee->setImg($newFilename);
        } catch (FileException $exception) {
            $this->addFlash('error', 'La photo du produit ne s\'est pas importée avec succès. Veuillez réessayer en modifiant le produit.');
        }
    }
}
