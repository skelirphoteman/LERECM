<?php

namespace App\Infrastructure\Contract;

use Doctrine\ORM\EntityManagerInterface;
use App\Domain\Contract\Entity\Contract;
use App\Infrastructure\Security\AccessService;

class ContractService
{
    private $entityManager;

    private $access;

    public function __construct(EntityManagerInterface $entityManager,
                                AccessService $access)
    {
        $this->entityManager = $entityManager;
        $this->access = $access;
    }

    private function insertContract(Contract $contract)
    {
        $em = $this->entityManager;
        $em->persist($contract);
        $em->flush();
    }

    private function removeContract(Contract $contract)
    {
        $em = $this->entityManager;
        $em->remove($contract);
        $em->flush();
    }

    public function addContract(Contract $contract) : ?String
    {
        $subscription = $this->access->subscriptionIsValid();
        if(!$subscription)
        {
            $this->insertContract($contract);
        }else {
            return $subscription;
        }
        return null;
    }

    public function nextPayment(Contract $contract): ?String
    {
        if(!$contract->nextPaymentIsValid())
        {
            return "Prochain payment impossible à valider. La date est supérieur à la date de fin de contrat.";
        }

        $nextPayment = $contract->getNextPaymentAt();

        if($contract->getContractType() == 0)
        {
            $nextPayment = $contract->getNextPaymentAt()->modify('+1 month');
        }
        if($contract->getContractType() == 1)
        {
            $nextPayment->add(new \DateInterval('P3M'));
        }
        if($contract->getContractType() == 2)
        {
            $nextPayment->add(new \DateInterval('P6M'));
        }
        if($contract->getContractType() == 3)
        {
            $nextPayment->add(new \DateInterval('P1Y'));
        }

        $contract->setNextPaymentAt($nextPayment);

        $this->insertContract($contract);
        return null;
    }


    public function deleteContract(Contract $contract) : ?String
    {
        $subscription = $this->access->subscriptionIsValid();
        if(!$subscription)
        {
            $this->removeContract($contract);
        }else {
            return $subscription;
        }
        return null;
    }

}