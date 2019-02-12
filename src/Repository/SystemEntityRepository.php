<?php

namespace App\Repository;

use App\Entity\SystemEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SystemEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method SystemEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method SystemEntity[]    findAll()
 * @method SystemEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SystemEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SystemEntity::class);
    }

//    /**
//     * @return SystemEntity[] Returns an array of SystemEntity objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SystemEntity
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
