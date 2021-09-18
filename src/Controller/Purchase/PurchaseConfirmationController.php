<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Cart\CartService;
use App\Entity\PurchaseItem;
use App\Form\CartConfirmationType;
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


    public function __construct( CartService $cartService,EntityManagerInterface $entityManager
    ) {

        $this->cartService = $cartService;
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/purchase/confirm", name="purchase_confirm")
     * IsGranted("ROLE_USER", "Please login to confirm your order")
     */
    public function confirm(Request $request)
    {


        // $form = $this->formFactory->create(CartConfirmationType::class);
        $form = $this->createForm(CartConfirmationType::class);
        $form->handleRequest($request);


        if (!$form->isSubmitted()) {
            $this->addFlash('warning', 'Please confirm your order');
            return $this->redirectToRoute('cart_show');
        }

        $user = $this->getUser();

        $cartItems = $this->cartService->getDetailCartItems();

        if (count($cartItems) == 0) {
            $this->addFlash('warning', 'Your cart is empty');
            return $this->redirectToRoute('cart_show');
    
        }


        /** @var Purchase */
        $purchase = $form->getData();
        $purchase
            ->setUser($user)
            ->setPurchasedAt(new \DateTime());

        $total = $this->cartService->getTotal();
        $purchase->setTotal($total);

        $this->entityManager->persist($purchase);

        foreach ($cartItems as $cartItem) {
            $purchaseItem = new PurchaseItem();
            $purchaseItem
                ->setPurchase($purchase)
                ->setProduct($cartItem->product)
                ->setProductName($cartItem->product->getName())
                ->setQuantity($cartItem->quantity)
                ->setProductPrice($cartItem->product->getPrice())
                ->setTotal($cartItem->getTotal());

            $this->entityManager->persist($purchaseItem);
          
        }

       


        $this->entityManager->flush();

        $this->cartService->emptyCart();

        // $flashBag->add('success', 'Your order has been confirmed');
        $this->addFlash('success', 'Your order has been confirmed');
        return $this->redirectToRoute('purchases_index');
        // return new RedirectResponse($this->router->generate('purchases_index', ['id' => $purchase->getId()]));
    }
}
