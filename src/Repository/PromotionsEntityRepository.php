<?php

namespace App\Repository;

use App\Entity\PromotionsEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PromotionsEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method PromotionsEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method PromotionsEntity[]    findAll()
 * @method PromotionsEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromotionsEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PromotionsEntity::class);
    }

//    /**
//     * @return PromotionsEntity[] Returns an array of PromotionsEntity objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PromotionsEntity
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
