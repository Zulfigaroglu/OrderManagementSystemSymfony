<?php

namespace App\Entity;

use App\Entity\Infrastructure\AbstractEntityWithSoftDelete;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=Orderepository::class)
 * @ORM\Table(name="`order`")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 */
class Order extends AbstractEntityWithSoftDelete
{
    protected $serializeFields = [
        'id',
        'customer',
        'total',
        'discountedTotal',
        'orderProducts',
        'createdAt',
        'updatedAt',
        'deletedAt',
    ];

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Müşteri bilgisi olmadan sipariş kaydedilemez.")
     */
    private ?Customer $customer;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\Positive(message="Sipariş tutarı 0 veya negatif değer olamaz.")
     */
    private float $total = 0;

    /**
     * @ORM\Column(name="discounted_total", type="decimal", precision=10, scale=2)
     */
    private float $discountedTotal = 0;

    /**
     * @ORM\OneToMany(targetEntity=OrderProduct::class, mappedBy="order", orphanRemoval=true)
     * @Assert\Collection(
     *     fields={
     *         "quantity" = @Assert\Positive(message="Ürün sayısı 0 veya negatif değer olamaz."),
     *         "total" = @Assert\Positive(message="Ürün tutarı 0 veya negatif değer olamaz."),
     *         "items" = @Assert\NotNull(message="Sepette bilinmeyen ürün.")
     *     },
     *     allowMissingFields=true,
     *     allowExtraFields=true
     * )
     */
    private Collection $orderProducts;

    public function __construct()
    {
        parent::__construct();
        $this->orderProducts = new ArrayCollection();
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

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

    /**
     * @return Collection<int, OrderProduct>
     */
    public function getOrderProducts(): Collection
    {
        return $this->orderProducts;
    }

    public function addOrderProduct(OrderProduct $orderProduct): self
    {
        if (!$this->orderProducts->contains($orderProduct)) {
            $this->orderProducts[] = $orderProduct;
            $orderProduct->setOrder($this);
        }

        return $this;
    }

    public function removeOrderProduct(OrderProduct $orderProduct): self
    {
        if ($this->orderProducts->removeElement($orderProduct)) {
            // set the owning side to null (unless already changed)
            if ($orderProduct->getOrder() === $this) {
                $orderProduct->setOrder(null);
            }
        }

        return $this;
    }

    /**
     * @param Collection<int, OrderProduct> $orderProducts
     * @return $this
     */
    public function setOrderProducts(Collection $orderProducts): self
    {
        foreach ($orderProducts as $orderProduct) {
            $this->addOrderProduct($orderProduct);
        }

        return $this;
    }

    public function clearOrderProducts(): self
    {
        $orderProducts = $this->getOrderProducts();
        foreach ($orderProducts as $orderProduct) {
            $this->removeOrderProduct($orderProduct);
        }

        return $this;
    }

    public function jsonSerialize(): array
    {
        $data = [
            'id' => $this->getId(),
            'total' => $this->getTotal(),
            'discountedTotal' => $this->getDiscountedTotal(),
            'customerId' => $this->getCustomer()->getId(),
            'createdAt' => $this->getCreatedAt(),
            'updatedAt' => $this->getUpdatedAt(),
            'deletedAt' => $this->getDeletedAt(),
        ];

        foreach ($this->getOrderProducts() as $orderProduct) {
            $item = [
                'productId' => $orderProduct->getProduct()->getId(),
                'unitPrice' => $orderProduct->getProduct()->getPrice(),
                'quantity' => $orderProduct->getQuantity(),
                'total' => $orderProduct->getTotal()
            ];

            $data['items'][] = $item;
        }

        return $data;
    }
}
