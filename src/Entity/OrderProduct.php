<?php

namespace App\Entity;

use App\Entity\Infrastructure\AbstractEntityWithSoftDelete;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass=OrderProductRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 */
class OrderProduct extends AbstractEntityWithSoftDelete
{
    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="orderProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Order $order;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Product $product;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $quantity;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private ?float $total;

    /**
     * @ORM\Column(name="discounted_total", type="decimal", precision=10, scale=2)
     */
    private ?string $discountedTotal;

    public function __construct()
    {
        parent::__construct();
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getDiscountedTotal(): ?float
    {
        return $this->discountedTotal;
    }

    public function setDiscountedTotal(float $discountedTotal): self
    {
        $this->discountedTotal = $discountedTotal;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'orderId' => $this->getOrder()->getId(),
            'productId' => $this->getProduct()->getId(),
            'quantity' => $this->getQuantity(),
            'total' => $this->getTotal(),
            'discountedTotal' => $this->getDiscountedTotal(),
            'createdAt' => $this->getCreatedAt(),
            'updatedAt' => $this->getUpdatedAt(),
            'deletedAt' => $this->getDeletedAt(),
        ];
    }
}
