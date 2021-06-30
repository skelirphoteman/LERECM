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

    /*private function sendEmail(User $user, $password): void
    {
        $email = (new Email())
            ->from('contact@skelirscreation.fr')
            ->to($user->getEmail() )
            ->subject('Création de votre epace client')
            ->html('
                <h1>Bienvenue chez Skelir\'s Creation !</h1>
                <p>Nous avons le plaisir de vous informer que nous vous avons crée votre espace personnelle sur notre site de gestion client.</p>
                <p>Vous pourrez retrouver tous les abonnements auxquels vous avez souscrit chez nous. De plus, vous pourrez retrouver tous documents important que nous devons vous faire parvenir.</p>
                <h2>Informations de connexions</h2>
                <p>Voici ci-dessous vos informations de connexion. Pour la sécurité de votre compte, lors de votre 1ère connexion nous vous inviterons à changer votre mot de passe.</p>
                <ul>
                    <li> Adresse mail : ' . $user->getEmail() .' </li>
                    <li> Mot de passe : ' . $password .' </li>
                </ul>            
                <p>Vous pouvez vous connecter dès maintenant ici <a href="http://localhost:5000/"></a></p>
            ');

        $this->mailer->send($email);
    }*/


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
        $password = $this->generatePassword(6);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            $password
        ));
        $em = $this->entityManager;
        $em->persist($user);
        //$em->flush();

        //$this->sendEmail($user, $password);

        $this->createUserMailer->send($user->getEmail(), [
            'password' => $password
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
}