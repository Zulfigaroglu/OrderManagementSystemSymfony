<?php

namespace App\Controller;

use App\Controller\Infrastructure\AbstractController;
use App\Service\Infrastructure\CategoryServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * @Route("/api/categories")
 */
class CategoryController extends AbstractController
{
    /**
     * @var CategoryServiceInterface
     */
    protected CategoryServiceInterface $categoryService;

    /**
     * @param ValidatorInterface $validator
     * @param CategoryServiceInterface $categoryService
     */
    public function __construct(ValidatorInterface $validator, CategoryServiceInterface $categoryService)
    {
        parent::__construct($validator);
        $this->categoryService = $categoryService;
    }

    /**
     * @Route("", name="category_list", methods={"GET","HEAD"})
     */
    public function index(): Response
    {
        $categories = $this->categoryService->getAll();
        return new JsonResponse($categories);
    }

    /**
     * @Route("", name="category_create", methods={"POST"})
     */
    public function crate(Request $request): Response
    {
        /**
         * @var array $categoryData
         */
        $categoryData = $request->getContent();
        $category = $this->categoryService->create($categoryData);

        $this->validate($category);


        $this->categoryService->save($category);
        return new JsonResponse($category);
    }

    /**
     * @Route("/{id}", name="category_show", methods={"GET","HEAD"})
     */
    public function show(int $id): Response
    {
        $category = $this->categoryService->getById($id);
        return new JsonResponse($category);
    }

    /**
     * @Route("/{id}", name="category_update", methods={"PUT"})
     */
    public function update(int $id, Request $request): Response
    {
        /**
         * @var array $categoryData
         */
        $categoryData = $request->getContent();
        $category = $this->categoryService->getById($id);
        $category = $this->categoryService->update($category, $categoryData);

        $this->validate($category);

        $this->categoryService->save($category);
        return new JsonResponse($category);
    }

    /**
     * @Route("/{id}", name="category_delete", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $this->categoryService->deleteById($id);
        return new JsonResponse(['is_deleted' => true]);
    }
}
