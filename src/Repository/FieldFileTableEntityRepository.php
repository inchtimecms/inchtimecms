<?php

namespace App\Repository;

use App\Entity\FieldFileTableEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FieldFileTableEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method FieldFileTableEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method FieldFileTableEntity[]    findAll()
 * @method FieldFileTableEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FieldFileTableEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FieldFileTableEntity::class);
    }

//    /**
//     * @return FieldFileTableEntity[] Returns an array of FieldFileTableEntity objects
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
    public function findOneBySomeField($value): ?FieldFileTableEntity
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
