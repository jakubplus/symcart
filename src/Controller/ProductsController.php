<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductsController extends AbstractController
{

    /**
     * List products
     *
     * @Route("/api/products/{page}", name="products_list", methods={"GET"})
     * @param int $limit
     * @param int $offset
     * @return Response
     */
    public function list(
        ManagerRegistry $doctrine,
        int $limit = 3,
        int $page = 0
    ): Response {
        $offset = $page > 0 ? ($page*$limit)-$limit : $page;
        $products = $doctrine->getRepository(Product::class)->findPaginated($limit, $offset);

        return $this->json($products);
    }

    /**
     * Add product
     *
     * @Route("/api/products/add", name="products_add", methods={"POST"})
     * @return Response
     */
    public function add(
        ManagerRegistry $doctrine,
        Request $request,
        ValidatorInterface $validator
    ): Response {
        $entityManager = $doctrine->getManager();

        $title = $request->request->get('title');
        $price = $request->request->get('price');

        if(!$title || !is_string($title) || !$price || !is_numeric($price)) {
            return $this->json(['status' => 'error', 'message' => 'Invalid values for title or price.'], 422);
        }

        $product = new Product();
        $product->setTitle($title);
        $product->setPrice($price);
        $product->setCurrency('USD');

        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return $this->json(['status' => 'error', 'message' => 'Invalid values for title or price.'], 422);
        }

        $entityManager->persist($product);

        $entityManager->flush();

        return $this->json(['status' => 'success', 'id' => $product->getId()], 200);
    }

    /**
     * Edit product
     *
     * @Route("/api/products/edit/{id}", name="products_edit", methods={"PUT", "PATCH"}, requirements={"id"="\d+"})
     * @param int $id
     * @return Response
     */
    public function edit(
        Request $request,
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            return $this->json(['status' => 'error', 'message' => 'No product with id: '.$id], 404);
        }
        $data = json_decode(
            $request->getContent(),
            true
        );

        if (isset($data['title'])) {
            if(empty($data['title']) || !is_string($data['title'])) {
                return $this->json(['status' => 'error', 'message' => 'Missing/wrong value for title'], 422);
            }
            $product->setTitle($data['title']);
        }

        if (isset($data['price'])) {
            if(empty($data['price']) || !is_numeric($data['price'])) {
                return $this->json(['status' => 'error', 'message' => 'Missing/wrong value for price'], 422);
            }
            $product->setPrice($data['price']);
        }

        $entityManager->flush();

        return $this->json(['status' => 'success', 'id' => $product->getId()], 200);
    }

    /**
     * Delete product
     *
     * @Route("/api/products/delete/{id}", name="products_delete", methods={"DELETE"}, requirements={"id"="\d+"})
     * @return Response
     */
    public function delete(ManagerRegistry $doctrine, int $id): Response {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            return $this->json(['status' => 'error', 'message' => 'No product with id: '.$id], 404);
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->json(['status' => 'success'], 200);
    }

}