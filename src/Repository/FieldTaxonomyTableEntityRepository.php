<?php

namespace App\Repository;

use App\Entity\FieldTaxonomyTableEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FieldTaxonomyTableEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method FieldTaxonomyTableEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method FieldTaxonomyTableEntity[]    findAll()
 * @method FieldTaxonomyTableEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FieldTaxonomyTableEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FieldTaxonomyTableEntity::class);
    }

//    /**
//     * @return FieldTaxonomyTableEntity[] Returns an array of FieldTaxonomyTableEntity objects
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
    public function findOneBySomeField($value): ?FieldTaxonomyTableEntity
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
