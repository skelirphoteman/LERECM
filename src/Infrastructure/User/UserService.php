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

    private function sendEmail($password) : void
    {

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

    public function createUser($user) : User
    {
        $user->setCreatedAt(new \DateTime('now'));
        $user->setState(1);
        $password = $this->generatePassword(15);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            $password
        ));
        $em = $this->entityManager;
        $em->persist($user);
        //$em->flush();

        $this->sendEmail($password);

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

    public function createAccount($user, $admin) : ?String
    {
        if(!$this->accessIsValid($admin)){
            return "Vous ne pouvez pas ajouter de facture. Veuillez vérifier que votre abonnement est toujours valide.";
        }

        if($this->emailExist($user->getEmail()))
        {
            return "Cette adresse email appartient déjà à un compte.";
        }

        $this->createUser($user);

        return null;
    }
}