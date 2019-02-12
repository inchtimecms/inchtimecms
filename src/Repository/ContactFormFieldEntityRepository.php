<?php

namespace App\Repository;

use App\Entity\ContactFormFieldEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ContactFormFieldEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactFormFieldEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactFormFieldEntity[]    findAll()
 * @method ContactFormFieldEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactFormFieldEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ContactFormFieldEntity::class);
    }

//    /**
//     * @return ContactFormFieldEntity[] Returns an array of ContactFormFieldEntity objects
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
    public function findOneBySomeField($value): ?ContactFormFieldEntity
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
