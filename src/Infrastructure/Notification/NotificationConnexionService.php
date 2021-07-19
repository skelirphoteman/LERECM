<?php

namespace App\Infrastructure\Notification;

use App\Domain\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;


use App\Infrastructure\SkelirMailer\SkelirMailerInterface;

class NotificationConnexionService
{

    private $notificationConnexion;

    public function __construct(EntityManagerInterface $entityManager,
                                SkelirMailerInterface $notificationConnexion)
    {
        $this->notificationConnexion = $notificationConnexion;
    }

    public function newConnexion(User $user) : ?String
    {
        $this->notificationConnexion->send($user->getEmail(), []);

        return null;
    }
}