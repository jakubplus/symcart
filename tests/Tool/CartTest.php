<?php

namespace App\Tests\Tool;

use App\Tool\Cart;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartTest extends WebTestCase
{

    public function setUp(): void {
        parent::setUp();
    }

    public function testHasNoProducts() {

        $cart = [
            'products' => []
        ];

        $this->assertEquals(false, Cart::hasProducts($cart));

    }

    public function testHasProducts() {

        $cart = [
            'products' => [
                1 => ['q' => 1, 'p' => 9.99]
            ],
            'total' => 9.99
        ];

        $this->assertEquals(true, Cart::hasProducts($cart));

    }

    public function testHasProduct() {

        $cart = [
            'products' => [
                1 => ['q' => 3, 'p' => 1.99],
                2 => ['q' => 2, 'p' => 2.99],
                3 => ['q' => 1, 'p' => 3.99],
            ],
            'total' => 15.94
        ];

        $this->assertEquals(true, Cart::hasProduct(2, $cart));

    }

    public function testGetProductsIds() {

        $cart = [
            'products' => [
                1 => ['q' => 3, 'p' => 1.99],
                2 => ['q' => 2, 'p' => 2.99],
                3 => ['q' => 1, 'p' => 3.99],
            ],
            'total' => 15.94
        ];

        $this->assertEquals([1,2,3], Cart::getProductsIds($cart));

    }

    public function testGetProduct() {

        $cart = [
            'products' => [
                1 => ['q' => 3, 'p' => 1.99],
                2 => ['q' => 2, 'p' => 2.99],
                3 => ['q' => 1, 'p' => 3.99],
            ],
            'total' => 15.94
        ];

        $this->assertEquals(['q' => 2, 'p' => 2.99], Cart::getProduct(2, $cart));

    }

    public function testGetProductsCount() {
        $cart = [
            'products' => [
                1 => ['q' => 3, 'p' => 1.99],
                2 => ['q' => 2, 'p' => 2.99],
                3 => ['q' => 1, 'p' => 3.99],
            ],
            'total' => 15.94
        ];

        $this->assertEquals(3, Cart::getProductsCount($cart));
    }

    public function testIsProductInvalid() {
        $product = ['id' => 1, 'p' => 1.99];

        $this->assertEquals(false, Cart::isProductValid($product));
    }

    public function testIsProductValid() {
        $product = ['q' => 3, 'p' => 1.99];

        $this->assertEquals(true, Cart::isProductValid($product));
    }

    public function testAddProduct() {
        $cart = [
            'products' => [
                1 => ['q' => 3, 'p' => 1.99],
                2 => ['q' => 2, 'p' => 2.99],
                3 => ['q' => 1, 'p' => 3.99],
            ],
            'total' => 15.94
        ];

        $product_to_add = ['id' => 4, 'q' => 1, 'p' => 4.99];

        $updated_cart = Cart::addProduct($product_to_add, $cart);
        $this->assertCount(4, $updated_cart['products']);
        $this->assertEquals(4.99, $updated_cart['products'][4]['p']);
        $this->assertEquals(20.93, $updated_cart['total'], 2);
    }

    public function testDeleteProduct() {
        $cart = [
            'products' => [
                1 => ['q' => 3, 'p' => 1.99],
                2 => ['q' => 2, 'p' => 2.99],
                3 => ['q' => 1, 'p' => 3.99],
            ],
            'total' => 15.94
        ];

        $updated_cart = Cart::deleteProduct(2, $cart);
        $this->assertCount(2, $updated_cart['products']);
        $this->assertEquals(3.99, $updated_cart['products'][3]['p']);
        $this->assertEquals(9.96, $updated_cart['total'], 2);
    }

    public function tearDown(): void {
        parent::tearDown();


    }
}
