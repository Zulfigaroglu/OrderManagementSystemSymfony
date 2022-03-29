<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Exception\NotFoundException;
use App\Repository\CustomerRepository;
use App\Repository\OrderProductRepository;
use App\Repository\OrderRepository;
use App\Service\Infrastructure\OrderServiceInterface;
use App\Service\Infrastructure\ProductServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OrderService implements OrderServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $_em;

    /**
     * @var ProductServiceInterface
     */
    protected ProductServiceInterface $productService;

    /**
     * @var OrderRepository
     */
    protected OrderRepository $orderRepository;

    /**
     * @var CustomerRepository
     */
    protected CustomerRepository $customerRepository;

    /**
     * @var OrderProductRepository
     */
    protected OrderProductRepository $orderProductRepository;

    /**
     * @param EntityManagerInterface $em
     * @param OrderRepository $orderRepository
     * @param ProductServiceInterface $productService
     * @param CustomerRepository $customerRepository
     * @param OrderProductRepository $orderProductRepository
     */
    public function __construct(
        EntityManagerInterface  $em,
        OrderRepository         $orderRepository,
        ProductServiceInterface $productService,
        CustomerRepository      $customerRepository,
        OrderProductRepository  $orderProductRepository
    )
    {
        $this->_em = $em;
        $this->productService = $productService;
        $this->orderRepository = $orderRepository;
        $this->customerRepository = $customerRepository;
        $this->orderProductRepository = $orderProductRepository;
    }

    /**
     * @return Order[]
     */
    public function getAll(): array
    {
        return $this->orderRepository->findAll();
    }

    /**
     * @param int $id
     * @return Order
     * @throws NotFoundException
     */
    public function getById(int $id): Order
    {
        $order = $this->orderRepository->find($id);

        if (!$order) {
            throw new NotFoundException();
        }

        return $order;
    }

    /**
     * @param array $orderData
     * @return Order
     */
    public function create(array $orderData): Order
    {
        try {
            $order = new Order();
            $this->updateProperties($order, $orderData);

            $itemsData = $orderData['items'];
            foreach ($itemsData as $itemData) {
                $productId = $itemData['productId'];
                $quantity = $itemData['quantity'];
                $this->attachProductById($order, $productId, $quantity);
            }

            $total = $this->calculateTotal($order);
            $order->setTotal($total);
            $order->setDiscountedTotal($total);
            return $order;
        } catch (\Exception $e) {
            //TODO: Handle exceptions
            throw new HttpException(500, $e->getMessage());
        }
    }

    /**
     * @param Order $order
     * @param array $orderData
     * @return Order
     */
    public function update(Order $order, array $orderData): Order
    {
        try {
            $this->updateProperties($order, $orderData);

            $itemsData = $orderData['items'];
            foreach ($itemsData as $itemData) {
                $orderProductId = $itemData['id'];
                $orderProduct = $order->getOrderProductById($orderProductId);
                $orderProduct->setQuantity($itemData['quantity']);
            }

            $total = $this->calculateTotal($order);
            $order->setTotal($total);
            $order->setDiscountedTotal($total);
            return $order;
        } catch (\Exception $e) {
            //TODO: Handle exceptions
            throw new HttpException(500, $e->getMessage());
        }
    }

    /**
     * @param Order $order
     * @return void
     */
    public function save(Order $order)
    {
        try {
            $this->_em->beginTransaction();

            if ($order->getId()) {
                $order->getOrderProducts()->forAll(function (int $index, OrderProduct $orderProduct) {
                    $this->orderProductRepository->save($orderProduct);
                });
            }
            $this->orderRepository->save($order);

            $this->_em->commit();
        } catch (\Exception $e) {
            //TODO: Handle exceptions
        }
    }

    /**
     * @param Order $order
     * @return void
     */
    public function delete(Order $order)
    {
        try {
            $this->orderRepository->remove($order);
        } catch (\Exception $e) {
            //TODO: Handle exceptions
        }
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteById(int $id)
    {
        try {
            $order = $this->getById($id);
            $this->orderRepository->remove($order);
        } catch (\Exception $e) {
            //TODO: Handle exceptions
        }
    }

    /**
     * @param Order $order
     * @param array $orderData
     * @return void
     */
    public function updateProperties(Order $order, array $orderData)
    {
        if (array_key_exists('customerId', $orderData)) {
            $this->attachCustomerById($order, $orderData['customerId']);
        }
    }

    /**
     * @param Order $order
     * @param int $customerId
     * @return void
     */
    public function attachCustomerById(Order $order, int $customerId)
    {
        if (!$customerId) {
            $order->setCustomer(null);
            return;
        }

        $customer = $this->customerRepository->find($customerId);
        if ($customer) {
            $order->setCustomer($customer);
        }
    }

    /**
     * @param Order $order
     * @param int $productId
     * @param int $quantity
     * @return void
     */
    public function attachProductById(Order $order, int $productId, int $quantity = 1)
    {
        $orderProduct = new OrderProduct();
        $orderProduct->setQuantity($quantity);

        $order->addOrderProduct($orderProduct);

        $product = $this->productService->getById($productId);
        $orderProduct->setProduct($product);

        $total = $this->productService->calculateTotal($product, $quantity);
        $orderProduct->setTotal($total);
    }

    /**
     * @param Order $order
     * @return float
     */
    public function calculateTotal(Order $order): float
    {
        $orderProducts = $order->getOrderProducts();
        $total = array_reduce($orderProducts->toArray(), function ($total, OrderProduct $orderProduct) {
            return $total + $orderProduct->getTotal();
        }, 0);
        return $total;
    }
}