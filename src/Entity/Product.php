<?php

namespace App\Entity;

use App\Entity\Infrastructure\AbstractEntity;
use App\Entity\Infrastructure\SoftDeleteTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 */
class Product extends AbstractEntity
{
    use SoftDeleteTrait;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="Ürün adı giriniz.")
     * @Assert\NotBlank(message="Ürün adı boş olamaz.")
     */
    private ?string $name;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     *
     */
    private ?float $price;

    /**
     * @ORM\Column(type="integer")
     */
    private int $stock = 0;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     */
    private ?Category $category;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float|null $price
     * @return $this
     */
    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getStock(): ?int
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     * @return $this
     */
    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category|null $category
     * @return $this
     */
    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'categoryId' => $this->getCategory() ? $this->getCategory()->getId() : null,
            'price' => $this->getPrice(),
            'stock' => $this->getStock(),
            'createdAt' => $this->getCreatedAt(),
            'updatedAt' => $this->getUpdatedAt(),
            'deletedAt' => $this->getDeletedAt(),
        ];
    }
}
