<?php

namespace App\Infrastructure\Subscription;

use App\Domain\Subscription\Entity\Subscription;
use App\Domain\Company\Entity\Company;
use App\Domain\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class SubscriptionService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function addSubscription(Company $company, Subscription $subscription)
    {
        $em = $this->entityManager;
        $em->persist($company);
        $em->persist($subscription);
        $em->flush();
    }

    private function updateSubscription(Subscription $subscription)
    {
        $em = $this->entityManager;
        $em->persist($subscription);
        $em->flush();
    }

    private function createCompany(Subscription $subscription, User $user) : Company
    {
        $company = new Company();
        $company->addUser($user);
        $company->setSubscription($subscription);

        return $company;
    }

    private function subscriptionExist($id) : bool
    {
        $em = $this->entityManager;

        $subscription_find = $em->getRepository(Subscription::class)
            ->findOneBy(['id' => $id]);

        if(empty($subscription_find)){
            return false;
        }

        return true;
    }

    public function createSubscriptionFromSkelirPanel($subscription_panel_id, $endAt) : Subscription
    {
        $subscription = new Subscription();

        $subscription->setSubscriptionPanelId($subscription_panel_id);
        $subscription->setEndAt($endAt);

        return $subscription;
    }

    public function createSubscription($subscription, $user) : ?String
    {
        if($user->getCompany())
        {
            return "Cet utilisateur possède déjà un abonnement";
        }

        $company = $this->createCompany($subscription, $user);

        $this->addSubscription($company, $subscription);
        return null;
    }

    public function editSubscription($subscription) : ?String
    {
        if(!$this->subscriptionExist($subscription->getId())){
            return "Aucun Abonnement trouvé.";
        }

        $this->updateSubscription($subscription);
        return null;
    }
}