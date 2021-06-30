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

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    protected function sendMail(): ?String
    {
        try {
            $email = (new TemplatedEmail())
                ->from('no-reply@skelirscreation.fr')
                ->to($this->email)
                ->subject($this->subject)
                ->htmlTemplate($this->htmlTemplate)
                ->context($this->informations);

            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            // some error prevented the email sending; display an
            // error message or try to resend the message
        }

        return null;
    }
}