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
    //Voir la fiche du café
    #[Route("/admin/voir-le-cafe/{id}", name: "app_admin_view_coffee", methods: ["GET", "POST"])]
    public function viewCoffee(Request $request, CoffeeRepository $coffeeRepository): Response{

        // Récupération de l'URL actuelle : /admin/voir-le-café/id
        // Explode : Sépare l'URL en une liste : ['admin', 'voir-le-café', 'id']
        $coffeeUrlArray = explode("/",$request->getUri());

        // Récupération de l'élément en dernière position
        $coffeeId = $coffeeUrlArray[sizeof($coffeeUrlArray)-1];

        // Permet de récupérer le café actuel
        $currentCoffee = $coffeeRepository->find(['id'=>$coffeeId]);

        return $this->render('/admin/view/coffee.html.twig', [
            'currentCoffee' => $currentCoffee,
        ]);
    }

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

            $this->addFlash('success', 'Vous avez ajouté ' . $coffee->getName() . ' avec succès !');
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
            else if($originalPhoto){
                $coffee->setImg($originalPhoto);
            }

            $em->persist($coffee);
            $em->flush();

            $this->addFlash('success', 'Vous avez modifié ' . $coffee->getName() . ' avec succès !');
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

        $this->addFlash('error', 'Vous avez supprimé ' . $coffee->getName() . ' avec succès !');
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
