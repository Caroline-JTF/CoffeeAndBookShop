<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Food;
use App\Form\FoodFormType;
use App\Repository\FoodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class foodController extends AbstractController
{
    //Voir la fiche de la viennoiserie
    #[Route("/admin/voir-le-livre/{id}", name: "app_admin_view_food", methods: ["GET", "POST"])]
    public function viewFood(Request $request, FoodRepository $foodRepository): Response{
        
        $foodUrlArray = explode("/",$request->getUri());
        $foodId = $foodUrlArray[sizeof($foodUrlArray)-1];
        $currentFood = $foodRepository->find(['id'=>$foodId]);

        return $this->render('/admin/view/food.html.twig', [
            'currentFood' => $currentFood,
        ]);
    }

    //Ajout + liste des viennoiseries
    #[Route("/admin/gerez-vos-viennoiseries", name: "app_admin_add_food", methods: ["GET", "POST"])]
    public function food(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response{

        //Ajouter une viennoiserie
        $food = new food();

        $form = $this->createForm(FoodFormType::class, $food);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            /** @var UploadedFile $photo */
            $photo = $form->get('img')->getData();

            if($photo) {
                
                $this->handleFile($food, $photo, $slugger);
            }
            
            $em->persist($food);
            $em->flush();

            $this->addFlash('success', 'Vous avez ajouté ' . $food->getName() . ' avec succès !');
            return $this->redirectToRoute('app_admin_dashboard');
        }

        return $this->render('/admin/add/food.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //Modifiez une viennoiserie
    #[Route('/admin/modifiez-la-viennoiserie/{id}', name: 'app_admin_update_food', methods: ['GET', 'POST'])]
    public function updateFood(Food $food, EntityManagerInterface $em, Request $request, SluggerInterface $slugger): Response
    {
        $originalPhoto = $food->getimg();

        $form = $this->createForm(FoodFormType::class, $food, [
            'img' => $originalPhoto
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('img')->getData();

            if($photo) {
                $this->handleFile($food, $photo, $slugger);
            }
            else if($originalPhoto){
                $food->setimg($originalPhoto);
            }

            $em->persist($food);
            $em->flush();

            $this->addFlash('success', 'Vous avez modifié ' . $food->getName() . ' avec succès !');
            return $this->redirectToRoute('app_admin_dashboard');
        }

        return $this->render('admin/update/food.html.twig', [
            'form' => $form->createView(),
            'food' => $food,
        ]);
    }

    //Supprimez une viennoiserie
    #[Route('/admin/supprimez-la-viennoiserie/{id}', name: 'app_admin_delete_food')]
	public function deleteFood(Food $food, FoodRepository $repository, EntityManagerInterface $em): Response
	{
		$repository->remove($food);
        $em->flush();

        $this->addFlash('error', 'Vous avez supprimé ' . $food->getName() . ' avec succès !');
		return $this->redirectToRoute('app_admin_dashboard');
	}

    //Private function
    private function handleFile(Food $food, UploadedFile $photo, SluggerInterface $slugger): void
    {
        
        $extension = '.' . $photo->guessExtension();

        $safeFilename = $slugger->slug($food->getName());

        $newFilename = $safeFilename . '_' . uniqid() . $extension;

        try {
            $photo->move($this->getParameter('uploads_dir'), $newFilename);
            $food->setImg($newFilename);
        } catch (FileException $exception) {
            $this->addFlash('warning', 'La photo du produit ne s\'est pas importée avec succès. Veuillez réessayer en modifiant le produit.');
        } // end catch()
    }
}
