<?php


namespace App\Infrastructure\SkelirMailer;


use App\Infrastructure\SkelirMailer\SkelirMailerInterface;
use App\Infrastructure\SkelirMailer\SkelirMailer;
use Symfony\Component\Mailer\MailerInterface;

class AdminCreateSupportTicketSkelirMailer extends SkelirMailer implements SkelirMailerInterface
{


    public function send(String $email, array $informations): ?string
    {
        $this->subject = "Création d\'une nouvelle demande par " . $informations['company_name'];
        $this->htmlTemplate = "mail/admin/create_support_ticket.html.twig";
        $this->email = $email;
        $this->informations = $informations;

        $this->sendMail();
        return null;
    }
}