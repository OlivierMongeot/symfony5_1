<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Repository\PurchaseRepository;
use App\Stripe\StripeService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchasePaymentController extends AbstractController
{


    /**
     * @Route("/purchase/payment/{id}", name="purchase_payment")
     * IsGranted("ROLE_USER")
     */
    public function showCardFrom($id, StripeService $stripeService, PurchaseRepository $purchaseRepository)
    {
        $purchase = $purchaseRepository->find($id);

        if ( !$purchase  ||
        $purchase->getUser() != $this->getUser() ||
        $purchase->getStatus() === Purchase::STATUS_PAID)
        {
            return $this->redirectToRoute('cart_show');
        }

       $paymentIntent = $stripeService->stripeInstance($purchase);

        return $this->render('purchase/payment.html.twig', [
            'client_secret' => $paymentIntent->client_secret,
            'purchase' => $purchase,
            'publicKey' => $stripeService->getPublicKey()
        ]);
    }
}
