<?php

namespace App\Infrastructure\Notification;

use App\Domain\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Notifier\Bridge\Telegram\Reply\Markup\Button\InlineKeyboardButton;
use Symfony\Component\Notifier\Bridge\Telegram\Reply\Markup\InlineKeyboardMarkup;
use Symfony\Component\Notifier\Bridge\Telegram\TelegramOptions;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\ChatterInterface;

use App\Infrastructure\SkelirMailer\SkelirMailerInterface;
use App\Infrastructure\SkelirTelegram\SkelirTelegramInterface;

class NotificationConnexionService
{

    private $notificationConnexion;
    private $newConnection;

    public function __construct(EntityManagerInterface $entityManager,
                                SkelirMailerInterface $notificationConnexion,
                                SkelirTelegramInterface $newConnection)
    {
        $this->notificationConnexion = $notificationConnexion;
        $this->newConnection = $newConnection;
    }

    public function newConnexion(User $user) : ?String
    {
        $this->notificationConnexion->send($user->getEmail(), []);

        $this->newConnection->send(['name' => $user->getSurname() . " " . $user->getName()]);


        return null;
    }
}