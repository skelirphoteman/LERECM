<?php


namespace App\Infrastructure\SkelirMailer;


use App\Infrastructure\SkelirMailer\SkelirMailerInterface;
use App\Infrastructure\SkelirMailer\SkelirMailer;
use Symfony\Component\Mailer\MailerInterface;

class ResetPasswordSkelirMailer extends SkelirMailer implements SkelirMailerInterface
{


    public function send(String $email, array $informations): ?string
    {
        $this->subject = "Demande de réinitialisation de votre mot de passe !";
        $this->htmlTemplate = "mail/user/reset_password.html.twig";
        $this->email = $email;
        $this->informations = $informations;

        $this->sendMail();
        return null;
    }
}