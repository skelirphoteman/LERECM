<?php


namespace App\Infrastructure\SkelirTelegram;


use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportException;

use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\ChatterInterface;


abstract class SkelirTelegram
{

    protected $chatter;
    protected $subject;
    protected $message;
    protected $informations;
    protected $env;

    public function __construct($env,
                                ChatterInterface $chatter)
    {
        $this->chatter = $chatter;
        $this->env = $env;
    }

    protected function sendMessage(): ?String
    {
        
        try {
            $chatMessage = new ChatMessage(
                $this->subject . "\n" . $this->message
            );

            $this->chatter->send($chatMessage);

        } catch (TransportExceptionInterface $e) {
            // some error prevented the email sending; display an
            // error message or try to resend the message
        }

        return null;
    }
}