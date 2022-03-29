<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @param Category $category
     * @param bool $flush
     * @return void
     */
    public function save(Category $category, bool $flush = true): void
    {
        $this->_em->persist($category);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param Category $category
     * @param bool $flush
     * @return void
     */
    public function remove(Category $category, bool $flush = true): void
    {
        $this->_em->remove($category);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
