<?php

namespace App\Entity;

use App\Entity\Infrastructure\AbstractEntityWithSoftDelete;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 */
class Product extends AbstractEntityWithSoftDelete
{
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function setCategoryId($categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'categoryId' => $this->getCategory()->getId(),
            'price' => $this->getPrice(),
            'stock' => $this->getStock(),
            'createdAt' => $this->getCreatedAt(),
            'updatedAt' => $this->getUpdatedAt(),
            'deletedAt' => $this->getDeletedAt(),
        ];
    }
}
