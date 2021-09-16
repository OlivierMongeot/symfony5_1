<?php

namespace App\Controller\Purchase;

use App\Entity\User;
use Twig\Environment;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PurchasesListController extends AbstractController
{

    // private $security;
    // private $router;
    // private $twig;

    // public function __construct(Security $security, RouterInterface $router, Environment $twig)
    // {
    //     $this->security = $security;
    //     $this->router = $router;
    //     $this->twig = $twig;
    // }

    /**
     * @Route("/purchases", name="purchases_index")
     * IsGranted("ROLE_USER", message="Access Denied")
     */
    public function index()
    {

        /** @var User */
         $user = $this->getUser();
        // if (!$user) {
        //     throw new AccessDeniedException('You are not logged in');
        // }

        
        // $html = $this->twig->render('purchase/index.html.twig', [
        //     'purchases' => $user->getPurchases(),
        // ]);
        // return new Response($html);
        return $this->render('purchase/index.html.twig', [
            'purchases' => $user->getPurchases()
        ]);




    }
}
