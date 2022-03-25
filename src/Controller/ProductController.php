<?php

namespace App\Controller;

use App\Controller\Infrastructure\AbstractController;
use App\Service\Infrastructure\IProductService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/products")
 */
class ProductController extends AbstractController
{
    protected IProductService $productService;

    public function __construct(ValidatorInterface $validator,IProductService $productService)
    {
        parent::__construct($validator);
        $this->productService = $productService;
    }

    /**
     * @Route("", name="product_list", methods={"GET","HEAD"})
     */
    public function index(): Response
    {
        $categories = $this->productService->getAll();
        return new JsonResponse($categories);
    }

    /**
     * @Route("", name="product_create", methods={"POST"})
     */
    public function crate(Request $request): Response
    {
        $productData = $request->request->all();
        $product = $this->productService->create($productData);

        $errors = $this->validate($product);
        if(count($errors) > 0){
            return new JsonResponse(['errors' => $errors], 422);
        }

        $this->productService->save($product);
        return new JsonResponse($product);
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET","HEAD"})
     */
    public function show(int $id): Response
    {
        $product = $this->productService->getById($id);
        return new JsonResponse($product);
    }

    /**
     * @Route("/{id}", name="product_update", methods={"PUT"})
     */
    public function update(int $id, Request $request): Response
    {
        $productData = $request->request->all();
        $product = $this->productService->getById($id);
        $product = $this->productService->update($product, $productData);

        $errors = $this->validate($product);
        if(count($errors) > 0){
            return new JsonResponse(['errors' => $errors], 422);
        }

        $this->productService->save($product);
        return new JsonResponse($product);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $this->productService->deleteById($id);
        return new JsonResponse(['is_deleted' => true]);
    }
}
