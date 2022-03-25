<?php

namespace App\Service;

use App\Entity\Discount;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Enum\DiscountConditionSubject;
use App\Enum\DiscountConditionType;
use App\Enum\DiscountPolicySubject;
use App\Enum\DiscountPolicyType;
use App\Model\DiscountDetailModel;
use App\Model\OrderDiscountsModel;
use App\Repository\CategoryRepository;
use App\Repository\DiscountRepository;
use App\Repository\OrderRepository;
use App\Service\Infrastructure\IDiscountService;
use App\Service\Infrastructure\IOrderService;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DiscountService implements IDiscountService
{
    protected OrderService $orderService;
    protected DiscountRepository $discountRepository;
    protected CategoryRepository $categoryRepository;

    public function __construct(
        IOrderService          $orderService,
        DiscountRepository     $discountRepository,
        CategoryRepository     $categoryRepository
    )
    {
        $this->orderService = $orderService;
        $this->discountRepository = $discountRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return Discount[]
     */
    public function getAll(): array
    {
        return $this->discountRepository->findAll();
    }

    public function getById(int $id): Discount
    {
        return $this->discountRepository->find($id);
    }

    public function create(array $discountData): Discount
    {
        try {
            $discount = new Discount();
            $this->updateProperties($discount, $discountData);
            return $discount;
        } catch (\Exception $e) {
            //TODO: Handle exceptions
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function update(Discount $discount, array $discountData): Discount
    {
        try {
            $this->updateProperties($discount, $discountData);
            return $discount;
        } catch (\Exception $e) {
            //TODO: Handle exceptions
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function save(Discount $discount)
    {
        try {
            $this->discountRepository->save($discount);
        } catch (\Exception $e) {
            //TODO: Handle exceptions
        }
    }

    public function delete(Discount $discount)
    {
        try {
            $this->discountRepository->remove($discount);
        } catch (\Exception $e) {
            //TODO: Handle exceptions
        }
    }

    public function deleteById(int $id)
    {
        try {
            $discount = $this->getById($id);
            $this->discountRepository->remove($discount);
        } catch (\Exception $e) {
            //TODO: Handle exceptions
        }
    }

    public function updateProperties(Discount $discount, array $discountData)
    {
        if (array_key_exists('categoryId', $discountData)) {
            $this->attachCategoryById($discount, $discountData['categoryId']);
        }

        if (array_key_exists('name', $discountData)) {
            $discount->setName($discountData['name']);
        }

        if (array_key_exists('conditionType', $discountData)) {
            $discount->setConditionType($discountData['conditionType']);
        }

        if (array_key_exists('conditionSubject', $discountData)) {
            $discount->setConditionSubject($discountData['conditionSubject']);
        }

        if (array_key_exists('conditionValue', $discountData)) {
            $discount->setConditionValue($discountData['conditionValue']);
        }

        if (array_key_exists('policyType', $discountData)) {
            $discount->setPolicyType($discountData['policyType']);
        }

        if (array_key_exists('policySubject', $discountData)) {
            $discount->setPolicySubject($discountData['policySubject']);
        }

        if (array_key_exists('policyValue', $discountData)) {
            $discount->setPolicyValue($discountData['policyValue']);
        }
    }

    public function attachCategoryById(Discount $discount, ?int $categoryId)
    {
        if (!$categoryId) {
            $discount->setCategory(null);
            return;
        }

        $category = $this->categoryRepository->find($categoryId);
        if ($category) {
            $discount->setCategory($category);
        }
    }

    /**
     * @param int $orderId
     * @return OrderDiscountsModel
     * @throws Exception
     */
    public function calculate(int $orderId): OrderDiscountsModel
    {
        $orderDiscountModel = new OrderDiscountsModel();
        $orderDiscountModel->orderId = $orderId;

        $order = $this->orderService->getById($orderId);
        $discounts = $this->getAll();
        foreach ($discounts as $discount) {
            $discountDetailsModel = $this->applyDiscountToOrder($discount, $order);
            if ($discountDetailsModel) {
                $orderDiscountModel->totalDiscount += $discountDetailsModel->amount;
                $orderDiscountModel->discountedTotal += $discountDetailsModel->subtotal;
                $orderDiscountModel->discounts[] = $discountDetailsModel;
            }
        }
        return $orderDiscountModel;
    }

    /**
     * @param int $orderId
     * @return OrderDiscountsModel
     * @throws Exception
     */
    public function apply(int $id,int $orderId): ?Order
    {
        $discount = $this->discountRepository->find($id);
        $order = $this->orderService->getById($orderId);

        $appliableDiscounts = $this->calculate($orderId);
        $discountDetailWillApply = array_filter($appliableDiscounts->discounts,
            function (DiscountDetailModel $discountDetail) use ($discount){
            return $discountDetail->reason == $discount->getName();
        })[0] ?? null;

        if(!$discountDetailWillApply){
            return null;
        }

        $orderTotal = $order->getTotal() - $discountDetailWillApply->amount;
        $order->setTotal($orderTotal);
        $this->orderService->save($order);

        return $order;
    }

    /**
     * @param Discount $discount
     * @param Order $order
     * @return DiscountDetailModel|null
     * @throws Exception
     */
    public function applyDiscountToOrder(Discount $discount, Order $order): ?DiscountDetailModel
    {
        $discountCategory = $discount->getCategory();
        $discountCategoryId = $discountCategory ? $discountCategory->getId() : null;
        $items = $this->getItemsFromOrderByCategoryId($order, $discountCategoryId);
        if ($items->count() == 0) {
            return null;
        }

        $conditionSubjectValue = $this->getConditionSubjectValue($discount, $items);
        if (!$conditionSubjectValue) {
            return null;
        }

        $timesOfConditionMet = $this->getTimesOfConditionMet($discount, $conditionSubjectValue);
        if ($timesOfConditionMet == 0) {
            return null;
        }

        $amountToBeDiscounted = $this->getAmountToBeDiscounted($discount, $items);
        $discountAmount = $this->calculateDiscountAmount($discount, $amountToBeDiscounted, $timesOfConditionMet);

        $discountDetailModel = new DiscountDetailModel();
        $discountDetailModel->amount = $discountAmount;
        $discountDetailModel->reason = $discount->getName();
        $discountDetailModel->subtotal = $order->getTotal() - $discountAmount;
        return $discountDetailModel;
    }

    /**
     * @param Discount $discount
     * @param int $conditionSubjectValue
     * @return int
     * @throws Exception
     */
    protected function getTimesOfConditionMet(Discount $discount, int $conditionSubjectValue): int
    {
        switch ($discount->getConditionType()) {
            case DiscountConditionType::EACH_TIMES_OF_VALUE:
            {
                return $conditionSubjectValue / $discount->getConditionValue();
            }
            case DiscountConditionType::HIGHIER_THAN_VALUE:
            {
                return ($conditionSubjectValue > $discount->getConditionValue()) ? 1 : 0;
            }
        }
        throw new Exception("Discount Condition Type is not implemented!");
    }

    /**
     * @param Discount $discount
     * @param Collection $items
     * @return int
     * @throws Exception
     */
    protected function getConditionSubjectValue(Discount $discount, Collection $items): int
    {
        switch ($discount->getConditionSubject()) {
            case DiscountConditionSubject::TOTAL_PRICE:
            {
                $total = array_reduce($items->toArray(), function ($total, OrderProduct $orderProduct) {
                    $total += $orderProduct->getTotal();
                    return $total;
                }, 0);
                return $total;
            }
            case DiscountConditionSubject::ITEM_COUNT:
            {
                $totalItemsQuantity = array_reduce($items->toArray(), function ($totalQuantity, OrderProduct $orderProduct) {
                    $totalQuantity += $orderProduct->getQuantity();
                    return $totalQuantity;
                }, 0);
                return $totalItemsQuantity;
            }
            case DiscountConditionSubject::PRODUCT_QUANTITY:
            {
                /**
                 * @var OrderProduct $itemHasMaximumQuantity
                 */
                $itemHasMaximumQuantity = array_reduce($items->toArray(), function (?OrderProduct $prev, OrderProduct $current) {
                    if (!$prev) {
                        return $current;
                    }

                    return $prev->getQuantity() < $current->getQuantity() ? $prev : $current;
                });
                return $itemHasMaximumQuantity->getQuantity();
            }
        }
        throw new Exception("Discount Condition Subject is not implemented!");
    }

    /**
     * @param Order $order
     * @param int|null $catecoryId
     * @return Collection<int, OrderProduct>
     */
    protected function getItemsFromOrderByCategoryId(Order $order, ?int $catecoryId): Collection
    {
        $items = $order->getOrderProducts();

        if ($catecoryId) {
            $items = $items->filter(function (OrderProduct $orderProduct) use ($catecoryId) {
                return $orderProduct->getProduct()->getCategory()->getId() == $catecoryId;
            });
        }

        return $items;
    }

    protected function getAmountToBeDiscounted(Discount $discount, Collection $items): int
    {
        switch ($discount->getPolicySubject()) {
            case DiscountPolicySubject::ANY_ITEM:
            {
                return $items->first()->price;
            }
            case DiscountPolicySubject::CHEAPEST_ITEM:
            {
                /**
                 * @var OrderProduct $itemHasMinimumPrice
                 */
                $itemHasMinimumPrice = array_reduce($items->toArray(), function (?OrderProduct $prev, OrderProduct $current) {
                    if (!$prev) {
                        return $current;
                    }

                    return $prev->getProduct()->getPrice() < $current->getProduct()->getPrice() ? $prev : $current;
                });
                return $itemHasMinimumPrice->getProduct()->getPrice();
            }
            case DiscountPolicySubject::ORDER:
            {
                $total = array_reduce($items->toArray(), function ($total, OrderProduct $orderProduct) {
                    $total += $orderProduct->getTotal();
                    return $total;
                }, 0);
                return $total;
            }
        }
        throw new Exception("Discount Policy Subject is not implemented!");
    }

    /**
     * @param Discount $discount
     * @param float $amountToBeDiscounted
     * @param int $timesOfConditionMet
     * @return float
     * @throws Exception
     */
    protected function calculateDiscountAmount(Discount $discount, float $amountToBeDiscounted, int $timesOfConditionMet): float
    {
        switch ($discount->getPolicyType()) {
            case DiscountPolicyType::DISCOUNT_BY_PERCENTAGE:
            {
                return $amountToBeDiscounted * ($discount->getPolicyValue() / 100) * $timesOfConditionMet;
            }
            case DiscountPolicyType::DISCOUNT_BY_TOTAL:
            {
                return $discount->getPolicyValue() * $timesOfConditionMet;
            }
            case DiscountPolicyType::GIVE_FREE:
            {
                return $amountToBeDiscounted * $timesOfConditionMet;
            }
        }
        throw new Exception("Discount Policy Type is not implemented!");
    }
}