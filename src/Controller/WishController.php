<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WishController extends AbstractController
{


    #[Route('/wish/{id}', name: 'wish', requirements: ['id'=>'\d+'])]
    public function wishDetail(int $id,WishRepository $wishRepository): Response
    {
        return $this->render('wish/Wish.html.twig',['id' => $id,'wishes' => $wishRepository->findAll()]);
    }

    #[Route('/list',name: '_list')]
    public function wishList(WishRepository $wishRepository): Response
    {
        return $this->render('wish/WishList.html.twig', ['wishes' =>$wishRepository->findAll()]);
    }
}
