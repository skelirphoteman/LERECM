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
use App\Infrastructure\ActionListener\ActionListenerInterface;

class NotificationConnexionService
{

    private $notificationConnexion;
    private $newConnection;
    private $newConnectionActionListener;

    public function __construct(EntityManagerInterface $entityManager,
                                SkelirMailerInterface $notificationConnexion,
                                SkelirTelegramInterface $newConnection,
                                ActionListenerInterface $newConnectionActionListener)
    {
        $this->notificationConnexion = $notificationConnexion;
        $this->newConnection = $newConnection;
        $this->newConnectionActionListener = $newConnectionActionListener;
    }

    public function newConnexion(User $user) : ?String
    {
        $this->notificationConnexion->send($user->getEmail(), []);

        $this->newConnection->send(['name' => $user->getSurname() . " " . $user->getName()]);

        $this->newConnectionActionListener->create($user);

        return null;
    }
}