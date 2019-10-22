<?php

namespace App\Repository;

use App\Entity\ProjectInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProjectInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectInfo[]    findAll()
 * @method ProjectInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectInfoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProjectInfo::class);
    }

    public function findOne(): ?ProjectInfo
    {
        return $this->createQueryBuilder('pI')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

//    /**
//     * @return ProjectInfo[] Returns an array of ProjectInfo objects
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
    public function findOneBySomeField($value): ?ProjectInfo
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
