<?php

namespace App\Entity;

use App\Entity\Infrastructure\AbstractEntity;
use App\Entity\Infrastructure\SoftDeleteTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="`order`")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 */
class Order extends AbstractEntity
{
    use SoftDeleteTrait;

    /**
     * @var string[]
     */
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
     * @ORM\OneToMany(targetEntity=OrderProduct::class, mappedBy="order", orphanRemoval=true, cascade={"persist"})
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

    /**
     * @return Customer|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer|null $customer
     * @return $this
     */
    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotal(): ?float
    {
        return $this->total;
    }

    /**
     * @param float $total
     * @return $this
     */
    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getDiscountedTotal(): ?float
    {
        return $this->discountedTotal;
    }

    /**
     * @param float $discountedTotal
     * @return $this
     */
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

    /**
     * @param int $id
     * @return OrderProduct
     */
    public function getOrderProductById(int $id): OrderProduct
    {
        $orderProduct = $this->orderProducts->filter(function(OrderProduct $orderProduct) use ($id){
            return $orderProduct->getId() == $id;
        })->first();
        return $orderProduct;
    }

    /**
     * @param OrderProduct $orderProduct
     * @return $this
     */
    public function addOrderProduct(OrderProduct $orderProduct): self
    {
        if (!$this->orderProducts->contains($orderProduct)) {
            $this->orderProducts[] = $orderProduct;
            $orderProduct->setOrder($this);
        }

        return $this;
    }

    /**
     * @param OrderProduct $orderProduct
     * @return $this
     */
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

    /**
     * @return $this
     */
    public function clearOrderProducts(): self
    {
        $orderProducts = $this->getOrderProducts();
        foreach ($orderProducts as $orderProduct) {
            $this->removeOrderProduct($orderProduct);
        }

        return $this;
    }

    /**
     * @return array
     */
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
                'id' => $orderProduct->getId(),
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
