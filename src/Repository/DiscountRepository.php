<?php

namespace App\Repository;

use App\Entity\Discount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Discount|null find($id, $lockMode = null, $lockVersion = null)
 * @method Discount|null findOneBy(array $criteria, array $orderBy = null)
 * @method Discount[]    findAll()
 * @method Discount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiscountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Discount::class);
    }

    public function save(Discount $discount, bool $flush = true): void
    {
        $this->_em->persist($discount);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Discount $discount, bool $flush = true): void
    {
        $this->_em->remove($discount);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
