<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{

    protected $productRepository;
    protected $cart;


    public function __construct(ProductRepository $productRepository, CartService $cart)    
    {   
        $this->productRepository = $productRepository;
        $this->cart = $cart;
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id"="\d+"})
     */
    public function add($id): Response
    {

        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('The product does not exist');
        }
        if ($id) {
            $this->cart->add($id);
        }

        $this->addFlash('success', 'L\'article à bien été ajouté au panier');

        return $this->redirectToRoute('product_show', [
            'category_slug' => $product->getCategory()->getSlug(),
            'slug' => $product->getSlug()
        ]);
    }


    /**
     * @Route("/cart", name="cart_show")
     */
    public function show(): Response
    {
        $detailledCart = $this->cart->getDetailCartItems();
        // dd($detailledCart);
        $total = $this->cart->getTotal();
        return $this->render('cart/index.html.twig', [
            'items' => $detailledCart,
            'total' => $total
        ]);
    }

    /**
     * @Route("/cart/delete/{id}", name="cart_delete", requirements={"id"="\d+"})
     */
    public function delete(int $id): Response
    {

        $product = $this->productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException('Le produit n\'existe pas');
        }

        $this->cart->remove($id);

        $this->addFlash('success', 'L\'article à bien été supprimé du panier');
        return $this->redirectToRoute('cart_show');
    }


    /**
     * @Route("/cart/decrement/{id}", name="cart_decrement", requirements={"id"="\d+"})
     * @param CartService $cartService
     **/
    public function decrement(int $id): Response
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Le produit n\'existe pas');
        }
        $this->cart->decrement($id);
        $this->addFlash('success', 'L\'article à bien été décrémenté du panier');
        return $this->redirectToRoute('cart_show');
    }

    /**
     * @Route("/cart/increment/{id}", name="cart_increment", requirements={"id"="\d+"})
     * @param CartService $cartService
     **/
    public function increment(int $id): Response
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Le produit n\'existe pas');
        }
        $this->cart->increment($id);
        $this->addFlash('success', 'L\'article à bien été incrémenté du panier');
        return $this->redirectToRoute('cart_show');
    }
}
