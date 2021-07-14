<?php

namespace App\Repository;

use App\Entity\ImageGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImageGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageGroup[]    findAll()
 * @method ImageGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageGroup::class);
    }

    // /**
    //  * @return ImageGroup[] Returns an array of ImageGroup objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ImageGroup
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
