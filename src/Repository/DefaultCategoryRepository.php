<?php

namespace App\Repository;

use App\Entity\DefaultCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DefaultCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method DefaultCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method DefaultCategory[]    findAll()
 * @method DefaultCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DefaultCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DefaultCategory::class);
    }

    /**
     * @return DefaultCategory[] Returns an array of Category objects
     */
    public function findDefaultCategories()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id != :id')
            ->setParameter('id', 5)
            ->getQuery()
            ->execute();
    }

    // /**
    //  * @return DefaultCategory[] Returns an array of DefaultCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DefaultCategory
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
