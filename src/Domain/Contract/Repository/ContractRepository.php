<?php

namespace App\Domain\Contract\Repository;

use App\Domain\Contract\Entity\Contract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contract|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contract|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contract[]    findAll()
 * @method Contract[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContractRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contract::class);
    }

    // /**
    //  * @return Contract[] Returns an array of Contract objects
    //  */
    public function findContractByCompany($company)
    {

        $fields = array('c.id', 'c.title', 'c.next_payment_at');

        $query = $this->getEntityManager()->createQueryBuilder();
        $query
            ->select($fields)
            ->from('App\Domain\Contract\Entity\Contract', 'c')
            ->innerJoin('c.client', 'e')
            ->where('c.state = 1 OR c.state = 0')
            ->andWhere('e.company = :id')
            ->setParameter('id', $company)
            ->orderBy('c.next_payment_at', 'ASC')
        ;

        $results = $query->getQuery()->getResult();
        return $results;
    }

    /*
    public function findOneBySomeField($value): ?Contract
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
