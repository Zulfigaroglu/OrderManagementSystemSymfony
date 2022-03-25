<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Repository\CustomerRepository;
use App\Repository\OrderProductRepository;
use App\Repository\OrderRepository;
use App\Service\Infrastructure\IOrderService;
use App\Service\Infrastructure\IProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OrderService implements IOrderService
{
    protected EntityManagerInterface $_em;
    protected IProductService $productService;
    protected OrderRepository $orderRepository;
    protected OrderProductRepository $orderProductRepository;
    protected CustomerRepository $customerRepository;

    public function __construct(
        EntityManagerInterface $em,
        IProductService        $productService,
        OrderRepository        $orderRepository,
        OrderProductRepository $orderProductRepository,
        CustomerRepository     $customerRepository
    )
    {
        $this->_em = $em;
        $this->productService = $productService;
        $this->orderRepository = $orderRepository;
        $this->orderProductRepository = $orderProductRepository;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @return Order[]
     */
    public function getAll(): array
    {
        return $this->orderRepository->findAll();
    }

    public function getById(int $id): Order
    {
        return $this->orderRepository->find($id);
    }

    public function create(array $orderData): Order
    {
        try {
            $order = new Order();
            $this->updateProperties($order, $orderData);
            $this->setItems($order, $orderData['items']);

            $total = $this->calculateTotal($order);
            $order->setTotal($total);
            $order->setDiscountedTotal($total);
            return $order;
        } catch (\Exception $e) {
            //TODO: Handle exceptions
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function update(Order $order, array $orderData): Order
    {
        try {
            $this->updateProperties($order, $orderData);
            return $order;
        } catch (\Exception $e) {
            //TODO: Handle exceptions
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function save(Order $order)
    {
        try {
            $this->_em->beginTransaction();

            $items = clone $order->getOrderProducts();

            $order->clearOrderProducts();
            $this->orderRepository->save($order);

            foreach ($items as $item) {
                $item->setOrder($order);
                $this->orderProductRepository->save($item);
            }

            $this->_em->commit();
        } catch (\Exception $e) {
            echo $e->getMessage(); die;
            //TODO: Handle exceptions
        }
    }

    public function delete(Order $order)
    {
        try {
            $this->orderRepository->remove($order);
        } catch (\Exception $e) {
            //TODO: Handle exceptions
        }
    }

    public function deleteById(int $id)
    {
        try {
            $order = $this->getById($id);
            $this->orderRepository->remove($order);
        } catch (\Exception $e) {
            //TODO: Handle exceptions
        }
    }

    public function updateProperties(Order $order, array $orderData)
    {
        if (array_key_exists('customerId', $orderData)) {
            $this->attachCustomerById($order, $orderData['customerId']);
        }
    }

    /**
     * @param Order $order
     * @param array $orderProductsData
     * @return void
     */
    public function setItems(Order $order, array $orderProductsData)
    {
        foreach ($orderProductsData as $orderProductData) {
            $productId = $orderProductData['productId'];
            $quantity = $orderProductData['quantity'];
            $this->attachProductById($order, $productId, $quantity);
        }
    }

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

    public function attachProductById(Order $order, int $productId, int $quantity = 1)
    {
        $orderProduct = new OrderProduct();
        $orderProduct->setQuantity($quantity);

        $order->addOrderProduct($orderProduct);

        $product = $this->productService->getById($productId);
        $orderProduct->setProduct($product);

        $total = $this->productService->calculateTotal($productId, $quantity);
        $orderProduct->setTotal($total);
    }

    public function calculateTotal(Order $order): float
    {
        $orderProducts = $order->getOrderProducts();
        $total = array_reduce($orderProducts->toArray(), function ($total, OrderProduct $orderProduct) {
            return $total + $orderProduct->getTotal();
        }, 0);
        return $total;
    }
}