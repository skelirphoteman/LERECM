<?php

namespace App\Domain\ApiAdminConnection\Entity;

use App\Domain\ApiAdminConnection\Entity\ApiAdminConnection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApiAdminConnection|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiAdminConnection|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiAdminConnection[]    findAll()
 * @method ApiAdminConnection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiAdminConnectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiAdminConnection::class);
    }

    // /**
    //  * @return ApiAdminConnection[] Returns an array of ApiAdminConnection objects
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
    public function findOneBySomeField($value): ?ApiAdminConnection
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
