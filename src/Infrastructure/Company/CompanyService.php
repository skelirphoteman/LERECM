<?php

namespace App\Infrastructure\Company;

use App\Domain\Company\Entity\Company;
use App\Domain\Subscription\Entity\Subscription;
use App\Domain\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class CompanyService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createCompanyFromSkelirPanel($user_panel_id, User $user, Subscription $subscription) : Company
    {
        $company = new Company();

        $company->addUser($user);
        $company->setSubscription($subscription);
        $company->setUserPanelId($user_panel_id);

        return $company;
    }
}