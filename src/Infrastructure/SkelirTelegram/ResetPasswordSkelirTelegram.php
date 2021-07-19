<?php


namespace App\Infrastructure\SkelirTelegram;


use App\Infrastructure\SkelirTelegram\SkelirTelegramInterface;
use App\Infrastructure\SkelirTelegram\SkelirTelegram;

class ResetPasswordSkelirTelegram extends SkelirTelegram implements SkelirTelegramInterface
{


    public function send(array $informations): ?string
    {
    	$now = new \DateTime('now');
        $this->subject = "[Changement de mot de passe]";
        $this->message = "Nom : " . $informations['name'] . "\n" . $now->format('d:m:Y h:i');

        $this->sendMessage();

        return null;
    }
}
