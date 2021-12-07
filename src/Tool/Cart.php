<?php

namespace App\Tool;

class Cart {

    /**
     * Does the cart have products?
     *
     * @param array $cart
     * @return bool
     */
    public static function hasProducts(array $cart) {
        return isset($cart['products']) && is_array($cart['products']) && !empty($cart['products']);
    }

    /**
     * Does the cart have product?
     *
     * @param int $productId
     * @param array $cart
     * @return bool
     */
    public static function hasProduct(int $productId, array $cart) {
        return self::hasProducts($cart) && isset($cart['products'][$productId]);
    }

    /**
     * Get products IDS
     *
     * @param array $cart
     * @return array
     */
    public static function getProductsIds(array $cart) {
        return self::hasProducts($cart) ? array_keys($cart['products']) : [];
    }

    /**
     * Get product from cart
     *
     * @param int $productId
     * @param array $cart
     * @return false|mixed
     */
    public static function getProduct(int $productId, array $cart) {
        return self::hasProduct($productId, $cart) ? $cart['products'][$productId] : false;
    }

    /**
     * Get products cunt
     *
     * @param $cart
     * @return int
     */
    public static function getProductsCount(array $cart) {
        return self::hasProducts($cart) ? count($cart['products']) : 0;
    }

    /**
     * Is product array valid?
     *
     * @param array $product
     * @return bool
     */
    public static function isProductValid(array $product) {
        return empty(array_diff(array_keys($product), ['q', 'p'])) ? true : false;
    }

    /**
     * Append the product to the cart
     *
     * @param array $product
     * @param array $cart
     * @return array
     */
    public static function addProduct(array $product, array $cart) {
        if(!isset($cart['products'])) {
            $cart = ['products' => [], 'total' => 0];
        }
        if(!self::hasProduct($product['id'], $cart)) {
            $cart['products'][$product['id']] = ['p' => $product['p'], 'q' => $product['q']];
        }
        else {
            $q = $cart['products'][$product['id']]['q'];
            $cart['products'][$product['id']] = ['p' => $product['p'], 'q' => ($q+$product['q'])];
        }
        $cart['total']+= $product['q']*$product['p'];
        return $cart;
    }

    /**
     * Remove product from the cart
     *
     * @param int $productId
     * @param array $cart
     * @return array|false
     */
    public static function deleteProduct(int $productId, array $cart) {
        if(!self::hasProducts($cart) || !self::hasProduct($productId, $cart)) {
            return false;
        }
        $ptd = $cart['products'][$productId];

        $cart['total'] = round($cart['total'] - ($ptd['q']*$ptd['p']), 2);
        unset($cart['products'][$productId]);

        return $cart;
    }

}