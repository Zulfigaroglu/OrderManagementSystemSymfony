<?php

namespace App\Entity;

use App\Entity\Infrastructure\AbstractEntity;
use App\Entity\Infrastructure\SoftDeleteTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DiscountRepository")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 */
class Discount extends AbstractEntity
{
    use SoftDeleteTrait;

    protected $serializeFields = [
        'id',
        'name',
        'category',
        'conditionType',
        'conditionSubject',
        'conditionValue',
        'policyType',
        'policySubject',
        'policyValue',
        'createdAt',
        'updatedAt',
        'deletedAt',
    ];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class)
     */
    private ?Category $category;

    /**
     * @ORM\Column(name="condition_subject", type="string", length=255, columnDefinition="enum('total_price','product_quantity','item_count')")
     * @Assert\Choice(callback={"App\Enum\DiscountConditionSubject","toArray"}, message="İndirim koşulu konusu hatalı.")
     */
    private $conditionSubject;

    /**
     * @ORM\Column(name="condition_type", type="string", length=255, columnDefinition="enum('higher_than_value','each_times_of_value')")
     * @Assert\Choice(callback={"App\Enum\DiscountConditionType", "toArray"}, message="İndirim koşulu tipi hatalı.")
     */
    private $conditionType;

    /**
     * @ORM\Column(name="condition_value", type="integer")
     * @Assert\NotNull(message="İndirim koşulu değeri giriniz.")
     * @Assert\NotBlank(message="İndirim koşulu değeri boş olamaz.")
     */
    private $conditionValue;

    /**
     * @ORM\Column(name="policy_subject", type="string", length=255, columnDefinition="enum('order','any_item','cheapest_item')")
     * @Assert\Choice(callback={"App\Enum\DiscountPolicySubject", "toArray"}, message="İndirim konusu hatalı.")
     */
    private $policySubject;

    /**
     * @ORM\Column(name="policy_type", type="string", length=255, columnDefinition="enum('discount_by_percantage','discount_by_total','give_free')")
     * @Assert\Choice(callback={"App\Enum\DiscountPolicyType", "toArray"}, message="İndirim tipi hatalı.")
     */
    private $policyType;

    /**
     * @ORM\Column(name="policy_value", type="integer", nullable=true)
     */
    private $policyValue;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getConditionSubject()
    {
        return $this->conditionSubject;
    }

    /**
     * @param mixed $conditionSubject
     */
    public function setConditionSubject($conditionSubject): void
    {
        $this->conditionSubject = $conditionSubject;
    }

    /**
     * @return mixed
     */
    public function getConditionType()
    {
        return $this->conditionType;
    }

    /**
     * @param mixed $conditionType
     */
    public function setConditionType($conditionType): void
    {
        $this->conditionType = $conditionType;
    }

    /**
     * @return mixed
     */
    public function getConditionValue()
    {
        return $this->conditionValue;
    }

    /**
     * @param mixed $conditionValue
     */
    public function setConditionValue($conditionValue): void
    {
        $this->conditionValue = $conditionValue;
    }

    /**
     * @return mixed
     */
    public function getPolicySubject()
    {
        return $this->policySubject;
    }

    /**
     * @param mixed $policySubject
     */
    public function setPolicySubject($policySubject): void
    {
        $this->policySubject = $policySubject;
    }

    /**
     * @return mixed
     */
    public function getPolicyType()
    {
        return $this->policyType;
    }

    /**
     * @param mixed $policyType
     */
    public function setPolicyType($policyType): void
    {
        $this->policyType = $policyType;
    }

    /**
     * @return mixed
     */
    public function getPolicyValue()
    {
        return $this->policyValue;
    }

    /**
     * @param mixed $policyValue
     */
    public function setPolicyValue($policyValue): void
    {
        $this->policyValue = $policyValue;
    }

}
