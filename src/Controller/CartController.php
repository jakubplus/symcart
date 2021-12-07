<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Tool\Cart;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Cookie;

class CartController extends AbstractController
{

    /**
     * List cart products
     *
     * @Route("/api/cart", name="cart_list", methods={"GET"})
     * @param int $limit
     * @param int $offset
     * @return Response
     */
    public function list(Request $request): Response {
        $cookie = $request->cookies->get('cart');
        if(!$cookie) {
            $cookie = Cookie::create('cart')
                ->withValue(json_encode(['products' => []]))
                ->withExpires((new \DateTime())->modify('+30 days'))
                ->withDomain('localhost')
                ->withSecure(true);
        }

        $response = new Response();
        $response->headers->setCookie($cookie);
        return $response;
    }

    /**
     * Add product to cart
     *
     * @Route("/api/cart/add", name="cart_add", methods={"POST"})
     * @return Response
     */
    public function add(Request $request): Response {
        $cookie = $request->cookies->get('cart');
        $cart = [];
        if(!$cookie) {
            $cookie = Cookie::create('cart');
            $cart = ['products' => [], 'total' => 0];
        }
        else {
            $cart = json_decode($cookie->getValue(), true);
        }
        $data = json_decode($request->getContent(), true);

        if(Cart::hasProducts($cart) && Cart::getProductsCount($cart)>=3) {
            throw new \Exception(
                'Cart can only have 3 products.'
            );
        }

        $cart = Cart::addProduct($data['product'], $cart);

        $cookie
            ->withValue(json_encode($cart))
            ->withExpires((new \DateTime())->modify('+30 days'))
            ->withDomain('localhost')
            ->withSecure(true);
        $response = new Response();
        $response->headers->setCookie($cookie);
        return $response;
    }

    /**
     * Delete product from the cart
     *
     * @Route("/api/cart/delete/{productId}", name="cart_delete", methods={"DELETE"}, requirements={"productId"="\d+"})
     * @return Response
     */
    public function delete(Request $request, int $productId): Response {
        $cookie = $request->cookies->get('cart');
        if(!$cookie) {
            $cookie = Cookie::create('cart');
        }
        else {
            $cookie
                ->withValue(json_encode(Cart::deleteProduct($productId, $cookie)));
        }
        $cookie
            ->withExpires((new \DateTime())->modify('+30 days'))
            ->withDomain('localhost')
            ->withSecure(true);

        $response = new Response();
        $response->headers->setCookie($cookie);
        return $response;
    }

}