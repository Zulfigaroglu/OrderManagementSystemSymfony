<?php

namespace App\Controller;

use App\Controller\Infrastructure\AbstractController;
use App\Service\Infrastructure\CustomerServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/customers")
 */
class CustomerController extends AbstractController
{
    protected CustomerServiceInterface $customerService;

    public function __construct(ValidatorInterface $validator, CustomerServiceInterface $customerService)
    {
        parent::__construct($validator);
        $this->customerService = $customerService;
    }

    /**
     * @Route("", name="customer_list", methods={"GET","HEAD"})
     */
    public function index(): Response
    {
        $categories = $this->customerService->getAll();
        return new JsonResponse($categories);
    }

    /**
     * @Route("", name="customer_create", methods={"POST"})
     */
    public function crate(Request $request): Response
    {
        /**
         * @var array $customerData
         */
        $customerData = $request->getContent();
        $customer = $this->customerService->create($customerData);

        $errors = $this->validate($customer);
        if(count($errors) > 0){
            return new JsonResponse(['errors' => $errors], 422);
        }

        $this->customerService->save($customer);
        return new JsonResponse($customer);
    }

    /**
     * @Route("/{id}", name="customer_show", methods={"GET","HEAD"})
     */
    public function show(int $id): Response
    {
        $customer = $this->customerService->getById($id);
        return new JsonResponse($customer);
    }

    /**
     * @Route("/{id}", name="customer_update", methods={"PUT"})
     */
    public function update(int $id, Request $request): Response
    {
        /**
         * @var array $customerData
         */
        $customerData = $request->getContent();
        $customer = $this->customerService->getById($id);
        $customer = $this->customerService->update($customer, $customerData);

        $errors = $this->validate($customer);
        if(count($errors) > 0){
            return new JsonResponse(['errors' => $errors], 422);
        }

        $this->customerService->save($customer);
        return new JsonResponse($customer);
    }

    /**
     * @Route("/{id}", name="customer_delete", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $this->customerService->deleteById($id);
        return new JsonResponse(['is_deleted' => true]);
    }
}
