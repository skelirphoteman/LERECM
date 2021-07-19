<?php


namespace App\Infrastructure\SkelirMailer;


use App\Infrastructure\SkelirMailer\SkelirMailerInterface;
use App\Infrastructure\SkelirMailer\SkelirMailer;
use Symfony\Component\Mailer\MailerInterface;

class NotificationConnexionSkelirMailer extends SkelirMailer implements SkelirMailerInterface
{


    public function send(String $email, array $informations): ?string
    {
        $this->subject = "Nouvelle connexion sur votre compte !";
        $this->htmlTemplate = "mail/connexion/new_connexion.html.twig";
        $this->email = $email;
        $this->informations = $informations;

        $this->sendMail();
        return null;
    }
}