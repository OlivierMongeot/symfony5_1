<?php

namespace App\Purchase;

use App\Cart\CartService;
use App\Entity\PurchaseItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class PurchasePersister{

    private $security;
    private $cartService;
    private $eM;

    public function __construct(Security $security, CartService $cartService, EntityManagerInterface $eM){
        $this->security = $security;
        $this->cartService = $cartService;
        $this->eM = $eM;
    }


    public function save($purchase){

        $cartItems = $this->cartService->getDetailCartItems();
        $user = $this->security->getUser();

        $purchase
        ->setUser($user);
        // ->setPurchasedAt(new \DateTime());

    // $total = $this->cartService->getTotal();
    // $purchase->setTotal($total);

    $this->eM->persist($purchase);

    foreach ($cartItems as $cartItem) {
        $purchaseItem = new PurchaseItem();
        $purchaseItem
            ->setPurchase($purchase)
            ->setProduct($cartItem->product)
            ->setProductName($cartItem->product->getName())
            ->setQuantity($cartItem->quantity)
            ->setProductPrice($cartItem->product->getPrice())
            ->setTotal($cartItem->getTotal());

        // $purchase->addPurchaseItem($purchaseItem);

        $this->eM->persist($purchaseItem);
      
    }


    $this->eM->flush();
    // $this->cartService->emptyCart();


    }
}