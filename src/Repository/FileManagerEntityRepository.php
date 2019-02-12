<?php

namespace App\Repository;

use App\Entity\FileManagedEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FileManagedEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method FileManagedEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method FileManagedEntity[]    findAll()
 * @method FileManagedEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FileManagerEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FileManagedEntity::class);
    }


//    /**
//     * @return FileManagedEntity[] Returns an array of FileManagedEntity objects
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
    public function findOneBySomeField($value): ?FileManagedEntity
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
