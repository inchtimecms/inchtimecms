<?php

namespace App\Repository;

use App\Entity\ShipFeeTemplateEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ShipFeeTemplateEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShipFeeTemplateEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShipFeeTemplateEntity[]    findAll()
 * @method ShipFeeTemplateEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShipFeeTemplateEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ShipFeeTemplateEntity::class);
    }

//    /**
//     * @return ShipFeeTemplateEntity[] Returns an array of ShipFeeTemplateEntity objects
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
    public function findOneBySomeField($value): ?ShipFeeTemplateEntity
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
