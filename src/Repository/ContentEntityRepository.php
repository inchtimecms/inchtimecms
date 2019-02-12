<?php

namespace App\Repository;

use App\Entity\ContentEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ContentEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContentEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContentEntity[]    findAll()
 * @method ContentEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContentEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ContentEntity::class);
    }

//    /**
//     * @return ContentEntity[] Returns an array of ContentEntity objects
//     */

    public function findByDeletedField($value,$maxResults)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.deleted = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?ContentEntity
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
