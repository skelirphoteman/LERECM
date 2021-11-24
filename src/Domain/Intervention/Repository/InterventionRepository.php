<?php

namespace App\Domain\Intervention\Repository;

use App\Domain\Intervention\Entity\Intervention;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Intervention|null find($id, $lockMode = null, $lockVersion = null)
 * @method Intervention|null findOneBy(array $criteria, array $orderBy = null)
 * @method Intervention[]    findAll()
 * @method Intervention[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterventionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Intervention::class);
    }


    public function findInterventionOfWeek(\DateTimeImmutable $date, int $company_id)
    {
        $days = $date->format('N') - 1;
        $start_week = $date->sub(new \DateInterval('P'. $days . 'D'));
        $diff_sunday = 6 - $days;
        $end_date = $start_week->add(new \DateInterval('P6D'));


        return $this->createQueryBuilder('i')
            ->join('i.client', 'c')
            ->andWhere('i.start_at BETWEEN :monday AND :sunday')
            ->andWhere('c.company = :company_id')
            ->setParameters([
                'monday' => $start_week->format('Y-m-d'),
                'sunday' => $end_date->format('Y-m-d'),
                'company_id'=> $company_id
            ])
            ->orderBy('i.start_at', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }



    public function findInterventionInCommingForClient(int $client_id)
    {
        $today = new \DatetimeImmutable();


        return $this->createQueryBuilder('i')
            ->join('i.client', 'c')
            ->andWhere('i.start_at >= :today')
            ->andWhere('c.id = :client_id')
            ->andWhere('i.is_visible = true')
            ->setParameters([
                'today' => $today->format('Y-m-d'),
                'client_id'=> $client_id
            ])
            ->orderBy('i.start_at', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findInterventionFinishForClient(int $client_id)
    {
        $today = new \DatetimeImmutable();


        return $this->createQueryBuilder('i')
            ->join('i.client', 'c')
            ->andWhere('i.start_at < :today')
            ->andWhere('c.id = :client_id')
            ->andWhere('i.is_visible = true')
            ->setParameters([
                'today' => $today->format('Y-m-d'),
                'client_id'=> $client_id
            ])
            ->orderBy('i.start_at', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }


    /*
    public function findOneBySomeField($value): ?Intervention
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
