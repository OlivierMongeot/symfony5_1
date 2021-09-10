<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{

    /**
     * @Route ("/", name="homePage")
     */
    public function homePage(EntityManagerInterface $em, ProductRepository $productRepository)
    {


        $products = $productRepository->findBy([], [], 3);
    
        return $this->render('home.html.twig', compact('products'));
    }
}