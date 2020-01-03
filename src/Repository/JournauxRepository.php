<?php

namespace App\Repository;

use App\Entity\Journaux;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Journaux|null find($id, $lockMode = null, $lockVersion = null)
 * @method Journaux|null findOneBy(array $criteria, array $orderBy = null)
 * @method Journaux[]    findAll()
 * @method Journaux[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JournauxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Journaux::class);
    }

    // /**
    //  * @return Journaux[] Returns an array of Journaux objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Journaux
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
