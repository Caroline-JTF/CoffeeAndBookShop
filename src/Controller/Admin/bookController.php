<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Form\BookFormType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class bookController extends AbstractController
{
    //Voir la fiche du livre
    #[Route("/admin/voir-le-livre/{id}", name: "app_admin_view_book", methods: ["GET", "POST"])]
    public function viewBook(Request $request, BookRepository $bookRepository): Response{

        //
        $bookUrlArray = explode("/",$request->getUri());
        $bookId = $bookUrlArray[sizeof($bookUrlArray)-1];
        $currentBook = $bookRepository->find(['id'=>$bookId]);

        return $this->render('/admin/view/book.html.twig', [
            'currentBook' => $currentBook,
        ]);
    }

    //Ajout + liste des livres
    #[Route("/admin/gerez-vos-livres", name: "app_admin_add_book", methods: ["GET", "POST"])]
    public function book(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response{

        //Ajouter un livre
        $book = new book();

        $form = $this->createForm(BookFormType::class, $book);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            /** @var UploadedFile $photo */
            $photo = $form->get('img')->getData();

            if($photo) {
                
                $this->handleFile($book, $photo, $slugger);
            }
            
            $em->persist($book);
            $em->flush();

            $this->addFlash('success', 'Vous avez ajouté ' . $book->getName() . ' avec succès !');
            return $this->redirectToRoute('app_admin_dashboard');
        }

        return $this->render('/admin/add/book.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //Modifiez un livre
    #[Route('/admin/modifiez-le-livre/{id}', name: 'app_admin_update_book', methods: ['GET', 'POST'])]
    public function updateBook(Book $book, EntityManagerInterface $em, Request $request, SluggerInterface $slugger): Response
    {
        $originalPhoto = $book->getimg();

        $form = $this->createForm(BookFormType::class, $book, [
            'img' => $originalPhoto
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('img')->getData();

            if($photo) {
                $this->handleFile($book, $photo, $slugger);
            }
            else if($originalPhoto){
                $book->setimg($originalPhoto);
            }

            $em->persist($book);
            $em->flush();

            $this->addFlash('info', 'Vous avez modifié ' . $book->getName() . ' avec succès !');
            return $this->redirectToRoute('app_admin_dashboard');
        }

        return $this->render('admin/update/book.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }

    //Supprimez un livre
    #[Route('/admin/supprimez-le-livres/{id}', name: 'app_admin_delete_book')]
	public function deleteBook(Book $book, BookRepository $repository, EntityManagerInterface $em): Response
	{
		$repository->remove($book);
        $em->flush();

        $this->addFlash('danger', 'Vous avez supprimé ' . $book->getName() . ' avec succès !');
		return $this->redirectToRoute('app_admin_dashboard');
	}

    //Private function
    private function handleFile(Book $book, UploadedFile $photo, SluggerInterface $slugger): void
    {
        
        $extension = '.' . $photo->guessExtension();

        $safeFilename = $slugger->slug($book->getName());

        $newFilename = $safeFilename . '_' . uniqid() . $extension;

        try {
            $photo->move($this->getParameter('uploads_dir'), $newFilename);
            $book->setImg($newFilename);
        } catch (FileException $exception) {
            $this->addFlash('warning', 'La photo du produit ne s\'est pas importée avec succès. Veuillez réessayer en modifiant le produit.');
        } // end catch()
    }
}
