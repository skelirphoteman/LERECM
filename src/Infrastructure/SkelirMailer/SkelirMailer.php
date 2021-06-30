<?php


namespace App\Infrastructure\SkelirMailer;


use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;

abstract class SkelirMailer
{

    protected $mailer;
    protected $subject;
    protected $htmlTemplate;
    protected $email;
    protected $informations;
    protected $env;

    public function __construct($env,
                                MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->env = $env;
    }

    protected function sendMail(): ?String
    {
        if($this->env == "dev")
        {
            $this->email = "test1@skelirscreation.fr";
        }
        try {
            $mail = (new TemplatedEmail())
                ->from('no-reply@skelirscreation.fr')
                ->to($this->email)
                ->subject($this->subject)
                ->htmlTemplate($this->htmlTemplate)
                ->context($this->informations);

            $this->mailer->send($mail);
        } catch (TransportExceptionInterface $e) {
            // some error prevented the email sending; display an
            // error message or try to resend the message
        }

        return null;
    }
}