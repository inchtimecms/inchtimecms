<?php

namespace App\Repository;

use App\Entity\MenuEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MenuEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenuEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenuEntity[]    findAll()
 * @method MenuEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MenuEntity::class);
    }

//    /**
//     * @return MenuEntity[] Returns an array of MenuEntity objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MenuEntity
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
