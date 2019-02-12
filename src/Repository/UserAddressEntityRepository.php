<?php

namespace App\Repository;

use App\Entity\UserAddressEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserAddressEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAddressEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAddressEntity[]    findAll()
 * @method UserAddressEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserAddressEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserAddressEntity::class);
    }

//    /**
//     * @return UserAddressEntity[] Returns an array of UserAddressEntity objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserAddressEntity
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
