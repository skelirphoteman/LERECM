<?php

namespace App\Infrastructure\UserSupport;

use Doctrine\ORM\EntityManagerInterface;
use App\Infrastructure\Security\AccessService;
use App\Domain\UserSupport\Entity\SupportTicket;
use App\Domain\UserSupport\Entity\SupportTicketMessage;
use App\Domain\User\Entity\User;


use App\Infrastructure\SkelirMailer\SkelirMailerInterface;

class SupportTicketService
{
    private $entityManager;
    private $accessService;
    private $adminSupportTicket;

    public function __construct(EntityManagerInterface $entityManager,
                                AccessService $accessService,
                                SkelirMailerInterface $adminCreateSupportTicket)
    {
        $this->accessService = $accessService;
        $this->entityManager = $entityManager;
        $this->adminSupportTicket = $adminCreateSupportTicket;
    }

    public function insertSupportTicket(SupportTicket $supportTicket)
    {
        $em = $this->entityManager;
        $em->persist($supportTicket);
        $em->flush();

        $this->adminSupportTicket->send("test1@skelirscreation.fr", [
            'company_name' => $supportTicket->getCompany()->getName()
        ]);
    }

    public function insertSupportTicketMessage(SupportTicketMessage $supportTicketMessage)
    {
        $em = $this->entityManager;
        $em->persist($supportTicketMessage);
        $em->flush();
    }

    public function createSupportTicket(SupportTicket $supportTicket, User $user): ?String
    {
        $supportTicket->setCreatedAt(new \DateTime('now'));


        $this->insertSupportTicket($supportTicket);
        return null;
    }

    public function createSupportTicketMessage(SupportTicketMessage $supportTicketMessage): ?String
    {
        $supportTicketMessage->setCreatedAt(new \DateTime('now'));


        $this->insertSupportTicketMessage($supportTicketMessage);
        return null;
    }
}