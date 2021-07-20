<?php


namespace App\Infrastructure\SkelirTelegram;


use App\Infrastructure\SkelirTelegram\SkelirTelegramInterface;
use App\Infrastructure\SkelirTelegram\SkelirTelegram;

class DayRapportSkelirTelegram extends SkelirTelegram implements SkelirTelegramInterface
{


    public function send(array $informations): ?string
    {
    	$now = new \DateTime('now');
        $this->subject = "[Rapport Journalier]";
        $this->message = $informations['message'];

        $this->sendMessage();

        return null;
    }
}