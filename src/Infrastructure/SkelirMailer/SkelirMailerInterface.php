<?php

namespace App\Infrastructure\SkelirMailer;

interface SkelirMailerInterface
{
    public function send(String $email, array $informations): ?String;

}