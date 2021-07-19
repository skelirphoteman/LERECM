<?php


namespace App\Infrastructure\SkelirTelegram;

interface SkelirTelegramInterface
{
    public function send(array $informations): ?String;

}