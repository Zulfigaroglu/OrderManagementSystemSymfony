<?php

namespace App\Controller;

use App\Controller\Infrastructure\AbstractController;
use App\Service\Infrastructure\DiscountServiceInterface;
use App\Service\Infrastructure\OrderServiceInterface;
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
    protected OrderServiceInterface $orderService;
    protected DiscountServiceInterface $discountService;

    public function __construct(ValidatorInterface $validator, OrderServiceInterface $orderService, DiscountServiceInterface $discountService)
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

        $this->validate($order);

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

        $this->validate($order);

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
