<?php

namespace App\Infrastructure\User;


use Doctrine\ORM\EntityManagerInterface;
use App\Domain\User\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private $entityManager;

    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager,
                                UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
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
}