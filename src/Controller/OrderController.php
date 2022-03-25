<?php

namespace App\Controller;

use App\Controller\Infrastructure\AbstractController;
use App\Service\Infrastructure\IDiscountService;
use App\Service\Infrastructure\IOrderService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/orders")
 */
class OrderController extends AbstractController
{
    protected IOrderService $orderService;
    protected IDiscountService $discountService;

    public function __construct(ValidatorInterface $validator, IOrderService $orderService, IDiscountService $discountService)
    {
        parent::__construct($validator);
        $this->orderService = $orderService;
        $this->discountService = $discountService;
    }

    /**
     * @Route("", name="order_list", methods={"GET","HEAD"})
     */
    public function index(): Response
    {
        $orders = $this->orderService->getAll();
        return new JsonResponse($orders);
    }

    /**
     * @Route("", name="order_create", methods={"POST"})
     */
    public function crate(Request $request): Response
    {
        /**
         * @var array $orderData
         */
        $orderData = $request->getContent();

        $order = $this->orderService->create($orderData);

        $errors = $this->validate($order);
        if(count($errors) > 0){
            return new JsonResponse(['errors' => $errors], 422);
        }

        $this->orderService->save($order);
        return new JsonResponse($order);
    }

    /**
     * @Route("/{id}", name="order_show", methods={"GET","HEAD"})
     */
    public function show(int $id): Response
    {
        $order = $this->orderService->getById($id);
        return new JsonResponse($order);
    }

    /**
     * @Route("/{id}", name="order_update", methods={"PUT"})
     */
    public function update(int $id, Request $request): Response
    {
        /**
         * @var array $orderData
         */
        $orderData = $request->getContent();
        $order = $this->orderService->getById($id);
        $order = $this->orderService->update($order, $orderData);

        $errors = $this->validate($order);
        if(count($errors) > 0){
            return new JsonResponse(['errors' => $errors], 422);
        }

        $this->orderService->save($order);
        return new JsonResponse($order);
    }

    /**
     * @Route("/{id}", name="order_delete", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $this->orderService->deleteById($id);
        return new JsonResponse(['is_deleted' => true]);
    }
}
