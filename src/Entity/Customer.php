<?php

namespace App\Entity;

use App\Entity\Infrastructure\AbstractEntityWithSoftDelete;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 * @UniqueEntity(
 *      fields="email",
 *      message="Bu mail adresi ile kayıtlı kullanıcı bulunmaktadır.",
 * )
 */
class Customer extends AbstractEntityWithSoftDelete
{
    protected $serializeFields = [
        'id',
        'name',
        'email',
        'emailVerfiedAt',
        'revenue',
        'orders',
        'createdAt',
        'updatedAt',
        'deletedAt',
    ];

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="İsim boş olamaz.")
     *
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotNull(message="Müşteri ismi giriniz")
     * @Assert\NotBlank(message="Müşteri e-mail adresi boş olamaz.")
     * @Assert\Email(message="Geçersiz e-mail adresi")
     */
    private ?string $email;

    /**
     * @ORM\Column(name="email_verfied_at", type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $emailVerfiedAt = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Parola boş olamaz.")
     * @Assert\Length(min="8", minMessage="Parola en az 8 karakterden oluşmalıdır.")
     */
    private ?string $password;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\PositiveOrZero(message="Bakiye negatif değer alamaz.")
     */
    private int $revenue = 0;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="customer", orphanRemoval=true)
     */
    private Collection $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEmailVerfiedAt(): ?\DateTimeImmutable
    {
        return $this->emailVerfiedAt;
    }

    public function setEmailVerfiedAt(\DateTimeImmutable $emailVerfiedAt): self
    {
        $this->emailVerfiedAt = $emailVerfiedAt;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRevenue(): ?string
    {
        return $this->revenue;
    }

    public function setRevenue(string $revenue): self
    {
        $this->revenue = $revenue;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setCustomer($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getCustomer() === $this) {
                $order->setCustomer(null);
            }
        }

        return $this;
    }
}
