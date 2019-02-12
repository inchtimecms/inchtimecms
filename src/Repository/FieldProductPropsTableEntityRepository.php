<?php

namespace App\Repository;

use App\Entity\FieldProductPropsTableEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FieldProductPropsTableEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method FieldProductPropsTableEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method FieldProductPropsTableEntity[]    findAll()
 * @method FieldProductPropsTableEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FieldProductPropsTableEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FieldProductPropsTableEntity::class);
    }

//    /**
//     * @return FieldProductPropsTableEntity[] Returns an array of FieldProductPropsTableEntity objects
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
    public function findOneBySomeField($value): ?FieldProductPropsTableEntity
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
