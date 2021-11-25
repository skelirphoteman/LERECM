<?php


namespace App\Infrastructure\SkelirMailer;


use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;
use App\Domain\AntiSpamMailer\Entity\AntiSpamMailer;
use Doctrine\ORM\EntityManagerInterface;
abstract class SkelirMailer
{

    protected $mailer;
    protected $subject;
    protected $htmlTemplate;
    protected $email;
    protected $informations;
    protected $env;
    private $em;

    public function __construct($env,
                                MailerInterface $mailer,
                                EntityManagerInterface $em)
    {
        $this->mailer = $mailer;
        $this->env = $env;
        $this->em = $em;
    }

    protected function sendMail(): ?String
    {
        if($this->emailIsSpam)
            return "L'adresse email contacté ne souhaite plus recevoir de mail de cette application. Veuillez lui signaler de réactivé son adresse mail.";

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

    protected function emailIsSpam()
    {
        $mailIsSpam = $this->em
            ->getRepository(AntiSpamMailer::class)
            ->findBy(['mail' => $this->email, 'is_disable' => false]);

        if($mailIsSpam)
            return true;

        return false;
    }
}