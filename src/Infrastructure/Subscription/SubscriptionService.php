<?php

namespace App\Infrastructure\Subscription;

use App\Domain\Subscription\Entity\Subscription;
use Doctrine\ORM\EntityManagerInterface;

class SubscriptionService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createSubscriptionFromSkelirPanel($subscription_panel_id, $endAt) : Subscription
    {
        $subscription = new Subscription();

        $subscription->setSubscriptionPanelId($subscription_panel_id);
        $subscription->setEndAt($endAt);

        return $subscription;
    }
}