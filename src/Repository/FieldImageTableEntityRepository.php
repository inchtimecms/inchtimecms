<?php

namespace App\Repository;

use App\Entity\FieldImageTableEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FieldImageTableEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method FieldImageTableEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method FieldImageTableEntity[]    findAll()
 * @method FieldImageTableEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FieldImageTableEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FieldImageTableEntity::class);
    }

//    /**
//     * @return FieldImageTableEntity[] Returns an array of FieldImageTableEntity objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FieldImageTableEntity
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
