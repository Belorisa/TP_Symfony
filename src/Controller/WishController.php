<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WishController extends AbstractController
{


    #[Route('/wish/{id}', name: '_wish', requirements: ['id'=>'\d+'])]
    public function wishDetail(Wish $wish): Response
    {
        return $this->render('wish/Wish.html.twig',['wish' => $wish]);
    }

    #[Route('/list',name: '_list')]
    public function wishList(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findAllWithCategories();
        return $this->render('wish/WishList.html.twig', ['wishes' =>$wishes]);
    }

    #[Route('/add',name: '_add')]
    public function addWish(Request $request,EntityManagerInterface $em): Response
    {
        $wish = new Wish();
        $form = $this->createForm(WishType::class, $wish);

        $form->handleRequest($request);

        if($form->isSubmitted() ){
            foreach ($wish->getCategories() as $category) {
                $category->addWish($wish);
            }
            $em->persist($wish);
            $em->flush();

            $this->addFlash('success', 'Wish added successfully');
            return $this->redirectToRoute('_wish',['id' => $wish->getId()]);
        }
        return $this->render('wish/WishForm.html.twig', ['wish_form' => $form,]);
    }

    #[Route('/edit/{id}',name: '_edit')]
    public function editWish(Request $request,EntityManagerInterface $em, Wish $wish): Response
    {
        $form = $this->createForm(WishType::class, $wish);

        $form->handleRequest($request);

        if($form->isSubmitted() ){
            foreach ($wish->getCategories() as $category) {
                $category->addWish($wish);
            }
            $em->persist($wish);
            $em->flush();

            $this->addFlash('success', 'Wish changed successfully');
            return $this->redirectToRoute('_wish',['id' => $wish->getId()]);
        }
        return $this->render('wish/WishForm.html.twig', ['wish_form' => $form,]);
    }

    #[Route('/delete/{id}',name: '_delete')]
    public function deleteWish(Wish $wish,EntityManagerInterface $em): Response
    {
        $wishToDelete = $em->getRepository(Wish::class)->find($wish->getId());
        $em->remove($wishToDelete);
        $em->flush();
        $this->addFlash('success', 'Wish deleted successfully');
        return $this->redirectToRoute("_list");
    }


}
