<?php

namespace App\Infrastructure\User;


use Doctrine\ORM\EntityManagerInterface;
use App\Domain\User\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Message;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Mime\Email;

use App\Infrastructure\SkelirMailer\SkelirMailerInterface;

class UserService
{
    private $entityManager;

    private $passwordEncoder;

    private $createUserMailer;

    public function __construct(EntityManagerInterface $entityManager,
                                UserPasswordEncoderInterface $passwordEncoder,
                                SkelirMailerInterface $createUserMailer)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->createUserMailer = $createUserMailer;
    }

    private function accessIsValid(User $user) : bool
    {
        if(!$user->getCompany()->getSubscription()->subIsValid()) return false;

        return true;
    }

    private function generatePassword($number = 10): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-?';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $number; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
    
    private function emailExist($email) : bool
    {
        $em = $this->entityManager;

        $user_find = $em->getRepository(User::class)
            ->findOneBy(['email' => $email]);

        if(empty($user_find)){
            return false;
        }

        return true;
    }

    private function insertUser(User $user)
    {
        $em = $this->entityManager;
        $em->persist($user);
        $em->flush();
    }

    public function createUserFromSkelirPanel($email) : User
    {
            $user = new User();

            $user->setEmail($email);
            $user->addRole('ROLE_COMPANY');
            $user->setCreatedAt(new \DateTime('now'));
            $user->setState(1);

            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $this->generatePassword(25)
            ));
        $em = $this->entityManager;
        $em->persist($user);
        $em->flush();

        return $user;
    }

    private function createUser($user) : User
    {
        $user->setCreatedAt(new \DateTime('now'));
        $user->setState(1);
        $password = $this->generatePassword(6);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            $password
        ));
        $this->insertUser($user);
        $this->createUserMailer->send($user->getEmail(), [
            'user_email' => $user->getEmail(),
            'user_password' => $password
        ]);

        return $user;
    }

    public function resetPassword(String $new_password, User $user) : void
    {
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            $new_password
        ));

        $em = $this->entityManager;
        $em->persist($user);
        $em->flush();
    }

    public function createAccount($user) : ?String
    {
        if($this->emailExist($user->getEmail()))
        {
            return "Cette adresse email appartient déjà à un compte.";
        }

        $this->createUser($user);

        return null;
    }

    public function editUserAccount(User $user) : ?String
    {
        $this->insertUser($user);
        return null;
    }
}