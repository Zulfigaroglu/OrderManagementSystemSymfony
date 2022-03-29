<?php

namespace App\Controller;

use App\Controller\Infrastructure\AbstractController;
use App\Exception\ValidationException;
use App\Service\Infrastructure\DiscountServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/discounts")
 */
class DiscountController extends AbstractController
{
    /**
     * @var DiscountServiceInterface
     */
    protected DiscountServiceInterface $discountService;

    /**
     * @param ValidatorInterface $validator
     * @param DiscountServiceInterface $discountService
     */
    public function __construct(ValidatorInterface $validator, DiscountServiceInterface $discountService)
    {
        parent::__construct($validator);
        $this->discountService = $discountService;
    }

    /**
     * @Route("", name="discount_list", methods={"GET","HEAD"})
     */
    public function index(): Response
    {
        $categories = $this->discountService->getAll();
        return new JsonResponse($categories);
    }

    /**
     * @Route("", name="discount_create", methods={"POST"})
     * @throws ValidationException
     */
    public function crate(Request $request): Response
    {
        /**
         * @var array $discountData
         */
        $discountData = $request->getContent();
        $discount = $this->discountService->create($discountData);

        $this->validate($discount);

        $this->discountService->save($discount);
        return new JsonResponse($discount);
    }

    /**
     * @Route("/{id}", name="discount_show", methods={"GET","HEAD"})
     */
    public function show(int $id): Response
    {
        $discount = $this->discountService->getById($id);
        return new JsonResponse($discount);
    }

    /**
     * @Route("/{id}", name="discount_update", methods={"PUT"})
     * @throws ValidationException
     */
    public function update(int $id, Request $request): Response
    {
        /**
         * @var array $discountData
         */
        $discountData = $request->getContent();
        $discount = $this->discountService->getById($id);
        $discount = $this->discountService->update($discount, $discountData);

        $this->validate($discount);

        $this->discountService->save($discount);
        return new JsonResponse($discount);
    }

    /**
     * @Route("/{id}", name="discount_delete", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $this->discountService->deleteById($id);
        return new JsonResponse(['is_deleted' => true]);
    }

    /**
     * @Route("/calculate/{orderId}", name="discount_calculate", methods={"POST"})
     */
    public function calculate(int $orderId): Response
    {
        $orderDiscounts = $this->discountService->calculate($orderId);
        return new JsonResponse($orderDiscounts);
    }

    /**
     * @Route("/{id}/apply/{orderId}", name="discount_apply", methods={"POST"})
     */
    public function apply(int $id,int $orderId): Response
    {
        $discountedOrder = $this->discountService->apply($id, $orderId);

        if(!$discountedOrder){
            return new JsonResponse(['errors' => ['message' => 'Bu indirim bu siparişe uygulanamaz.']], 422);
        }

        return new JsonResponse($discountedOrder);
    }
}
