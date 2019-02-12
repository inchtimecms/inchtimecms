<?php

namespace App\Repository;

use App\Entity\CartEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CartEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method CartEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method CartEntity[]    findAll()
 * @method CartEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CartEntity::class);
    }

//    /**
//     * @return CartEntity[] Returns an array of CartEntity objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CartEntity
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
