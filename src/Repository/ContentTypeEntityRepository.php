<?php

namespace App\Repository;

use App\Entity\ContentTypeEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ContentTypeEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContentTypeEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContentTypeEntity[]    findAll()
 * @method ContentTypeEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContentTypeEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ContentTypeEntity::class);
    }

//    /**
//     * @return ContentTypeEntity[] Returns an array of ContentTypeEntity objects
//     */

    public function findByDeletedField($value, $maxResults)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.deleted = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?ContentTypeEntity
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
