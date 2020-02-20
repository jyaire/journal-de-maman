<?php

namespace App\Repository;

use App\Entity\Articles;
use App\Entity\Journaux;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Articles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Articles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Articles[]    findAll()
 * @method Articles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticlesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Articles::class);
    }

    public function previous(Articles $article)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT u
            FROM App:Articles u
            WHERE u.jour = (SELECT MAX(us.jour) FROM App:Articles us WHERE us.jour < :jour )'
            )
            ->setParameter(':jour', $article->getJour())
            ->getOneOrNullResult();
    }

    public function next(Articles $article)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT u
            FROM App:Articles u
            WHERE u.jour = (SELECT MIN(us.jour) FROM App:Articles us WHERE us.jour > :jour )'
            )
            ->setParameter(':jour', $article->getJour())
            ->getOneOrNullResult();
    }

    // /**
    //  * @return Articles[] Returns an array of Articles objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Articles
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
