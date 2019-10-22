<?php

namespace App\Repository;

use App\Entity\ExternalSites;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ExternalSites|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExternalSites|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExternalSites[]    findAll()
 * @method ExternalSites[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExternalSitesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ExternalSites::class);
    }

//    /**
//     * @return ExternalSites[] Returns an array of ExternalSites objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExternalSites
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
