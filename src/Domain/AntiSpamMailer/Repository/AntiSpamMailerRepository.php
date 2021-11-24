<?php

namespace App\Domain\AntiSpamMailer\Repository;

use App\Domain\AntiSpamMailer\Entity\AntiSpamMailer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AntiSpamMailer|null find($id, $lockMode = null, $lockVersion = null)
 * @method AntiSpamMailer|null findOneBy(array $criteria, array $orderBy = null)
 * @method AntiSpamMailer[]    findAll()
 * @method AntiSpamMailer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AntiSpamMailerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AntiSpamMailer::class);
    }

    // /**
    //  * @return AntiSpamMailer[] Returns an array of AntiSpamMailer objects
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
    public function findOneBySomeField($value): ?AntiSpamMailer
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
