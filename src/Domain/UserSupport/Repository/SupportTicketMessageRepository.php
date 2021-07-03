<?php

namespace App\Domain\UserSupport\Repository;

use App\Domain\UserSupport\Entity\SupportTicketMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SupportTicketMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method SupportTicketMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method SupportTicketMessage[]    findAll()
 * @method SupportTicketMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupportTicketMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SupportTicketMessage::class);
    }

    // /**
    //  * @return SupportTicket[] Returns an array of SupportTicket objects
    //  */
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
    public function findOneBySomeField($value): ?SupportTicket
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
