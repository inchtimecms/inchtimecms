<?php

namespace App\Repository;

use App\Entity\TaxonomyTypeEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TaxonomyTypeEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaxonomyTypeEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method TaxonomyTypeEntity[]    findAll()
 * @method TaxonomyTypeEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaxonomyTypeEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TaxonomyTypeEntity::class);
    }

//    /**
//     * @return TaxonomyTypeEntity[] Returns an array of TaxonomyTypeEntity objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TaxonomyTypeEntity
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
