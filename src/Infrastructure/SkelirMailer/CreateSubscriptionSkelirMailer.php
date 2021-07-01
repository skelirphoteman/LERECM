<?php


namespace App\Infrastructure\SkelirMailer;


use App\Infrastructure\SkelirMailer\SkelirMailerInterface;
use App\Infrastructure\SkelirMailer\SkelirMailer;
use Symfony\Component\Mailer\MailerInterface;

class CreateSubscriptionSkelirMailer extends SkelirMailer implements SkelirMailerInterface
{


    public function send(String $email, array $informations): ?string
    {
        $this->subject = "Création de votre abonnement !";
        $this->htmlTemplate = "mail/user/subscription/create_subscription.html.twig";
        $this->email = $email;
        $this->informations = $informations;

        $this->sendMail();
        return null;
    }
}