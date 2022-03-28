<?php

namespace App\EventListener;

use App\Entity\OrderProduct;
use App\Service\Infrastructure\IProductService;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class OrderProductEventListener
{
    protected IProductService $productService;

    public function __construct(IProductService $productService)
    {
        $this->productService = $productService;
    }

    public function preUpdate(OrderProduct $orderProduct, PreUpdateEventArgs $args)
    {
        $product = $orderProduct->getProduct();

        if ($args->hasChangedField('quantity')) {
            $oldValue = $args->getOldValue('quantity');
            $newValue = $args->getNewValue('quantity');
            if($oldValue == $newValue){
                return;
            }


            $this->productService->increaseStock($product, $oldValue);
            $this->productService->decreaseStock($product, $newValue);
            $this->productService->save($product);
        }
    }

    public function postPersist(OrderProduct $orderProduct, LifecycleEventArgs $args)
    {
        $product = $orderProduct->getProduct();
        $this->productService->decreaseStock($product, $orderProduct->getQuantity());
        $this->productService->save($product);
    }
}