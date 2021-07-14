<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function confirmEmail($id)
    {
        $qb = $this->createQueryBuilder('u');
        $query = $qb->update()
            ->set('u.isActive', ':isActive')
            ->where('u.email = :email')
            ->setParameter('isActive', true)
            ->setParameter('email', $id)
            ->getQuery();
        $query->execute();
    }

    public function changePassword($id, $password)
    {
        $qb = $this->createQueryBuilder('u');
        $query = $qb->update()
            ->set('u.password', ':password')
            ->where('u.email = :email')
            ->setParameter('password', $password)
            ->setParameter('email', $id)
            ->getQuery();
        $query->execute();
    }

    public function findByCriteria($active, $locked, $roles)
    {
        $str_roles = implode($roles);

        $qb = $this->createQueryBuilder('u');
        $qb
        ->select('u')
        ->where($qb->expr()->like('u.roles', $qb->expr()->literal("%$str_roles%")))
        ->andWhere('u.isActive = :active')
        ->andWhere('u.isLocked = :locked')
        ->setParameter('active', $active)
        ->setParameter('locked', $locked);

        return $qb->getQuery()->getResult();
    }
}
