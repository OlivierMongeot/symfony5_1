<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Cart\CartService;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchasePaymentSuccessController extends AbstractController{



    /** 
    * @Route("/purchase/terminate/{id}", name="purchase_payment_success")
    * IsGranted("ROLE_USER")
    */
    public function success($id, PurchaseRepository $repository, CartService $cartService, EntityManagerInterface $em){

        $purchase = $repository->find($id);

        if(
        !$purchase  ||
        $purchase->getUser() != $this->getUser() ||
        $purchase->getStatus() === Purchase::STATUS_PAID){
            $this->addFlash('error', 'La commande n\'existe pas');   
            return $this->redirectToRoute('purchases_index');
        }


        $purchase->setStatus(Purchase::STATUS_PAID);
        $em->flush();
        // $em->persist($purchase);
        $cartService->emptyCart();
        $this->addFlash('success', 'Commande réalisée avec success !!');
        return $this->redirectToRoute('purchases_index');


    }
}