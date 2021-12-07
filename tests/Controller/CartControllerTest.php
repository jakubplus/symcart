<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CartControllerTest extends WebTestCase
{
    public function setUp(): void {
        parent::setUp();

    }

    public function testList(): void {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/cart');

        $this->assertResponseIsSuccessful();
    }

    public function testAdd() {
        $client = static::createClient();
        $crawler = $client->request('POST', '/api/cart/add', [], [], [], '{"product":{"id":4,"q":4,"p":7.22}}');

        $this->assertResponseIsSuccessful();
    }

    public function testDelete() {
        $client = static::createClient();
        $crawler = $client->request('DELETE', '/api/cart/delete/3');

        $this->assertResponseIsSuccessful();
    }

    public function tearDown(): void {
        parent::tearDown();


    }
}
