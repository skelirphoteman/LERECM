<?php


namespace App\Infrastructure\SkelirMailer;


use App\Infrastructure\SkelirMailer\SkelirMailerInterface;
use App\Infrastructure\SkelirMailer\SkelirMailer;
use Symfony\Component\Mailer\MailerInterface;

class CreateUserSkelirMailer extends SkelirMailer implements SkelirMailerInterface
{


    public function send(String $email, array $informations): ?string
    {
        $this->subject = "Bienvenue chez L.E.R.E.C.M !";
        $this->htmlTemplate = "mail/user/new_user.html.twig";
        $this->email = $email;
        $this->informations = $informations;

        $this->sendMail();
        return null;
    }
}