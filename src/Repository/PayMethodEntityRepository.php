<?php

namespace App\Repository;

use App\Entity\PayMethodEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PayMethodEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method PayMethodEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method PayMethodEntity[]    findAll()
 * @method PayMethodEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayMethodEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PayMethodEntity::class);
    }

//    /**
//     * @return PayMethodEntity[] Returns an array of PayMethodEntity objects
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
    public function findOneBySomeField($value): ?PayMethodEntity
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
