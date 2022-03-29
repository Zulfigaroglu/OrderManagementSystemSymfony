<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    /**
     * @param Customer $customer
     * @param bool $flush
     * @return void
     */
    public function save(Customer $customer, bool $flush = true): void
    {
        $this->_em->persist($customer);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param Customer $customer
     * @param bool $flush
     * @return void
     */
    public function remove(Customer $customer, bool $flush = true): void
    {
        $this->_em->remove($customer);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
