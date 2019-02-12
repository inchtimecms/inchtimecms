<?php

namespace App\Repository;

use App\Entity\UserPermissionGroupEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserPermissionGroupEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPermissionGroupEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPermissionGroupEntity[]    findAll()
 * @method UserPermissionGroupEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPermissionGroupEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserPermissionGroupEntity::class);
    }

    // /**
    //  * @return UserPermissionGroupEntity[] Returns an array of UserPermissionGroupEntity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserPermissionGroupEntity
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
