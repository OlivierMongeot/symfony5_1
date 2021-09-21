<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Cart\CartService;
use App\Entity\PurchaseItem;
use App\Form\CartConfirmationType;
use App\Purchase\PurchasePersister;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PurchaseConfirmationController extends AbstractController
{
    // protected $router;
    // protected $security;
    protected $cartService;
    protected $entityManager;
    protected $purchasePersister;


    public function __construct( CartService $cartService,EntityManagerInterface $entityManager, PurchasePersister $purchasePersister 
    ) {

        $this->cartService = $cartService;
        $this->entityManager = $entityManager;
        $this->purchasePersister = $purchasePersister;
    }


    /**
     * @Route("/purchase/confirm", name="purchase_confirm")
     * IsGranted("ROLE_USER", "Please login to confirm your order")
     */
    public function confirm(Request $request)
    {

        $form = $this->createForm(CartConfirmationType::class);
        $form->handleRequest($request);


        if (!$form->isSubmitted()) {
            $this->addFlash('warning', 'Please confirm your order');
            return $this->redirectToRoute('cart_show');
        }

       
        $cartItems = $this->cartService->getDetailCartItems();

        if (count($cartItems) == 0) {
            $this->addFlash('warning', 'Your cart is empty');
            return $this->redirectToRoute('cart_show');
    
        }


        /** @var Purchase */
        $purchase = $form->getData();
        $this->purchasePersister->save($purchase);
        // $this->addFlash('success', 'Your order has been confirmed');
        // return $this->redirectToRoute('purchases_index');
        return $this->redirectToRoute('purchase_payment', ['id' => $purchase->getId()]);
    }
}
