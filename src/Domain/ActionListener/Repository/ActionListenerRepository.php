<?php

namespace App\Domain\ActionListener\Repository;

use App\Domain\ActionListener\Entity\ActionListener;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ActionListener|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActionListener|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActionListener[]    findAll()
 * @method ActionListener[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionListenerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActionListener::class);
    }

     /**
      * @return ActionListener[] Returns an array of ActionListener objects
      */
    
    public function findRapportOneDay()
    {
        $now = new \Datetime('now');
        $yesterday = new \Datetime('-1 day');

        return $this->createQueryBuilder('a')
            ->where('a.created_at > :yasterday')
            ->setParameter('yasterday', $yesterday->format('Y-m-d'))
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?ActionListener
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
