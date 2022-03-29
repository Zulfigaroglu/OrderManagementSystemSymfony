<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * @param Order $order
     * @param bool $flush
     * @return void
     */
    public function save(Order $order, bool $flush = true): void
    {
        $this->_em->persist($order);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param Order $order
     * @param bool $flush
     * @return void
     */
    public function remove(Order $order, bool $flush = true): void
    {
        $this->_em->remove($order);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
