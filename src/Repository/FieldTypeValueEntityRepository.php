<?php

namespace App\Repository;

use App\Entity\FieldTypeValueEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FieldTypeValueEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method FieldTypeValueEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method FieldTypeValueEntity[]    findAll()
 * @method FieldTypeValueEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FieldTypeValueEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FieldTypeValueEntity::class);
    }

//    /**
//     * @return FieldTypeValueEntity[] Returns an array of FieldTypeValueEntity objects
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
    public function findOneBySomeField($value): ?FieldTypeValueEntity
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
