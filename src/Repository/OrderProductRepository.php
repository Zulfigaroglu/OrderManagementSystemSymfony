<?php

namespace App\Repository;

use App\Entity\OrderProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderProduct[]    findAll()
 * @method OrderProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderProduct::class);
    }

    public function save(OrderProduct $orderProduct, bool $flush = true): void
    {
        $this->_em->persist($orderProduct);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(OrderProduct $orderProduct, bool $flush = true): void
    {
        $this->_em->remove($orderProduct);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
