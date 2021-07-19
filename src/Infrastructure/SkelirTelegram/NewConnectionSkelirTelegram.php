<?php


namespace App\Infrastructure\SkelirTelegram;


use App\Infrastructure\SkelirTelegram\SkelirTelegramInterface;
use App\Infrastructure\SkelirTelegram\SkelirTelegram;

class NewConnectionSkelirTelegram extends SkelirTelegram implements SkelirTelegramInterface
{


    public function send(array $informations): ?string
    {
    	$now = new \DateTime('now');
        $this->subject = "[Nouvel connection d'utilisateur]";
        $this->message = "Nom : " . $informations['name'] . "\n" . $now->format('d:m:Y h:i');

        $this->sendMessage();

        return null;
    }
}