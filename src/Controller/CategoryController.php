<?php

namespace App\Controller;

use App\Controller\Infrastructure\AbstractController;
use App\Service\Infrastructure\ICategoryService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * @Route("/api/categories")
 */
class CategoryController extends AbstractController
{
    protected ICategoryService $categoryService;

    public function __construct(ValidatorInterface $validator, ICategoryService $categoryService)
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

        $errors = $this->validate($category);
        if(count($errors) > 0){
            return new JsonResponse(['errors' => $errors]);
        }

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

        $errors = $this->validate($category);
        if(count($errors) > 0){
            return new JsonResponse(['errors' => $errors]);
        }

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
