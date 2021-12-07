<?php

namespace App\Tests\Controller;

use App\DataFixtures\ProductFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductsControllerTest extends WebTestCase
{
    public function setUp(): void {
        parent::setUp();

    }

    public function testGetList(): void {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/products');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
    }

    public function testAddPostDataEmpty() {
        $client = static::createClient();
        $crawler = $client->request('POST', '/api/products/add');
        $this->assertResponseStatusCodeSame(422);
    }

    public function testAddPostDataWrongType() {
        $client = static::createClient();
        $crawler = $client->request('POST', '/api/products/add', [
            'title' => 'Aaaa', 'price' => 'XOXO'
        ]);
        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseFormatSame('json');
    }

    public function testAddPostData() {
        $client = static::createClient();
        $crawler = $client->request('POST', '/api/products/add', [
            'title' => 'Aaaa', 'price' => 8.88
        ]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseFormatSame('json');
    }

    public function testEditPostDataEmpty() {
        $client = static::createClient();
        $crawler = $client->request('PUT', '/api/products/edit/3');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testEditPostDataWrongType() {
        $client = static::createClient();
        $crawler = $client->request('PUT', '/api/products/edit/3', [
            'price' => 'XOXO'
        ]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseFormatSame('json');
    }

    public function testEditPostData() {
        $client = static::createClient();
        $crawler = $client->request('PUT', '/api/products/edit/3', [
            'title' => 'Bbbbb'
        ]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseFormatSame('json');
    }

    public function testDelete() {
        $client = static::createClient();
        $crawler = $client->request('DELETE', '/api/products/delete/3');
        $this->assertResponseStatusCodeSame(200);
    }

    public function tearDown(): void {
        parent::tearDown();


    }
}
