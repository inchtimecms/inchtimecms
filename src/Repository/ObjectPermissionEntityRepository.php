<?php

namespace App\Repository;

use App\Entity\ObjectPermissionEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ObjectPermissionEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method ObjectPermissionEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method ObjectPermissionEntity[]    findAll()
 * @method ObjectPermissionEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObjectPermissionEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ObjectPermissionEntity::class);
    }

    // /**
    //  * @return ObjectPermissionEntity[] Returns an array of ObjectPermissionEntity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ObjectPermissionEntity
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
