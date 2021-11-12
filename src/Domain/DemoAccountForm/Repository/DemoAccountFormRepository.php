<?php

namespace App\Domain\DemoAccountForm\Repository;

use App\Domain\DemoAccountForm\Entity\DemoAccountForm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DemoAccountForm|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemoAccountForm|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemoAccountForm[]    findAll()
 * @method DemoAccountForm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemoAccountFormRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemoAccountForm::class);
    }

    public function findIfIpAlreadyUser(String $ip)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.user_ip = :ip')
            ->andWhere('d.post_at > :date')
            ->setParameters(['ip' => $ip, 'date' => new \DateTime('- 1 days')])
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?DemoAccountForm
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
