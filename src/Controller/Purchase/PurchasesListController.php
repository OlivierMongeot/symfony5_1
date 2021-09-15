<?php

namespace App\Controller\Purchase;

use App\Entity\User;
use Twig\Environment;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PurchasesListController extends AbstractController
{


    /**
     * @Route("/purchases", name="purchases_index")
     * IsGranted("ROLE_USER", message="Access denied, you must be logged")
     */
    public function index()
    {

        // is loged ? 
        /** @var User */
        $user = $this->getUser();

        // if (!$user) {
        //     // $url = $this->router->generate('homePage');
        //     // return new RedirectResponse($url);
        //     throw new AccessDeniedException('You are not logged in');
        // }
        // Who is looged

        // passer a twig commande 
        // $html = $this->twig->render('purchase/index.html.twig', [
        //     'purchases' => $user->getPurchases(),
        // ]);
        // return new Response($html);
        return $this->render('purchase/index.html.twig', [
            'purchases' => $user->getPurchases(),
        ]);
    }
}
