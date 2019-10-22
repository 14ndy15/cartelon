<?php

namespace App\Repository;

use App\Entity\PosterEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PosterEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method PosterEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method PosterEvent[]    findAll()
 * @method PosterEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PosterEventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PosterEvent::class);
    }


    /**
     * @return PosterEvent[] Returns an array of PosterEvent objects
     */
    public function findByDateField($amount)
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.date', 'DESC')
            ->setMaxResults($amount)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return PosterEvent[] Returns an array of PosterEvent objects
     */
    public function orderByDate($amount)
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.date', 'DESC')
            ->orderBy('p.position', 'DESC')
            ->setMaxResults($amount)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return PosterEvent[] Returns an array of PosterEvent objects
     */
    public function findByDateFieldPosition($position, $amount)
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.date', 'DESC')
            ->orderBy('p.position', 'DESC')
            ->setMaxResults($amount)
            ->setFirstResult($position)
            ->getQuery()
            ->getResult()
            ;
    }


//    /**
//     * @return PosterEvent[] Returns an array of PosterEvent objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PosterEvent
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
