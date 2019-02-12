<?php

namespace App\Repository;

use App\Entity\FieldTextTableEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FieldTextTableEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method FieldTextTableEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method FieldTextTableEntity[]    findAll()
 * @method FieldTextTableEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FieldTextTableEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FieldTextTableEntity::class);
    }

//    /**
//     * @return FieldTextTableEntity[] Returns an array of FieldTextTableEntity objects
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
    public function findOneBySomeField($value): ?FieldTextTableEntity
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
