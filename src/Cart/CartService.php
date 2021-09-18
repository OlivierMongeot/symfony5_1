<?php

namespace App\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    protected $session;
    protected $total;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository =  $productRepository;
    }

    public function emptyCart(){
        $this->session->set('cart', []);
    }

    public function add(int $id)
    {
        $cart = $this->session->get('cart', []);
        if (!array_key_exists($id, $cart)) {
            $cart[$id] = 0;
        } 
        $cart[$id]++;
        $this->session->set('cart', $cart);
    }


    public function remove(int $id)
    {
        $cart = $this->session->get('cart', []);
        if (array_key_exists($id, $cart)) {
            unset($cart[$id]);
        }
        $this->session->set('cart', $cart);
    }

    /**
    * @return CartItem[]
    */
    public function getDetailCartItems()
    {

        $detailledCart = [];
        $cart = $this->session->get('cart', []);
        foreach ($cart as $id => $quantity) {
            $product = $this->productRepository->find($id);
            if ($product) {
                $detailledCart[] = new CartItem($product, $quantity);
            }
        }
        return $detailledCart;
    }

    public function getTotal()
    {
        $total = 0;
        $cart = $this->session->get('cart', []);
        foreach ($cart as $id => $quantity) {
            $product = $this->productRepository->find($id);
            if ($product) {
                $total += $product->getPrice() * $quantity;
            }
        }
        return $total;
    }

    public function decrement(int $id)
    {
        $cart = $this->session->get('cart', []);
        foreach ($cart as $idKey => $qty) {
            if ($idKey == $id) {
                $cart[$idKey]--;
                if ($cart[$idKey] == 0) {
                    unset($cart[$idKey]);
                }
            }
        }
        $this->session->set('cart', $cart);
    }

    public function increment(int $id)
    {
        $cart = $this->session->get('cart', []);
        foreach ($cart as $idKey => $qty) {
            if ($idKey == $id) {
                $cart[$idKey]++;
            }
        }
        $this->session->set('cart', $cart);
    }
}
