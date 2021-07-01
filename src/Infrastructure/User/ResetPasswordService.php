<?php


namespace App\Infrastructure\User;

use App\Domain\User\Entity\User;
use App\Domain\User\Entity\ResetPassword;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\Date;
use App\Infrastructure\SkelirMailer\SkelirMailerInterface;

class ResetPasswordService
{
    private $entityManager;

    private $userService;

    private $resetPasswordMailer;

    private $confirmResetPasswordMailer;

    public function __construct(EntityManagerInterface $entityManager,
                                UserService $userService,
                                SkelirMailerInterface $resetPasswordMailer,
                                SkelirMailerInterface $confirmResetPasswordMailer)
    {
        $this->entityManager = $entityManager;
        $this->userService = $userService;
        $this->resetPasswordMailer = $resetPasswordMailer;
        $this->confirmResetPasswordMailer = $confirmResetPasswordMailer;

    }

    private function findUser($email) : ?User
    {
        return $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => $email]);
    }

    private function findResetPassword($user) : ?ResetPassword
    {
        return $this->entityManager
            ->getRepository(ResetPassword::class)
            ->findOneBy(['user' => $user]);
    }

    private function generateToken() : String
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 50; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    private function insertResetPassword($user){
        $resetPassword = new ResetPassword();

        $resetPassword->setUser($user);
        $resetPassword->setCreatedAt(new \DateTime('now'));
        $token = $this->generateToken();
        $resetPassword->setToken($token);
        $resetPassword->setState(0);

        $this->entityManager->persist($resetPassword);
        $this->entityManager->flush();

        $this->resetPasswordMailer->send($user->getEmail(), [
            'reset_token' => $token
        ]);
    }

    private function updateResetPassword($resetPassword){

        $resetPassword->setCreatedAt(new \DateTime('now'));
        $token = $this->generateToken();
        $resetPassword->setToken($token);
        $resetPassword->setState(0);

        $this->entityManager->persist($resetPassword);
        $this->entityManager->flush();

        $this->resetPasswordMailer->send($resetPassword->getUser()->getEmail(), [
            'reset_token' => $token
        ]);
    }

    private function dateIsValid($date) : bool
    {
        $datetime = new \DateTime('-30 minutes');
        if($datetime < $date){
            return true;
        }
        return false;
    }

    private function resetPasswordSuccessfully(ResetPassword $resetPassword)
    {
        $resetPassword->setState(1);

        $this->entityManager->persist($resetPassword);
        $this->entityManager->flush();


        $this->confirmResetPasswordMailer->send($resetPassword->getUser()->getEmail(), []);
    }

    public function createToken($email) : ?String
    {
        $user = $this->findUser($email);

        if(!$user){
            return "Cet adresse email n'est lié à aucun compte entreprise.";
        }
        
        $resetPassword = $this->findResetPassword($user);
        if(!$resetPassword){
            $this->insertResetPassword($user);

            return null;
        }else{
            if($this->dateIsValid($resetPassword->getCreatedAt())){
                return "Une demande à déjà été faite il y a moins de 30 minutes.";
            }else{
                $this->updateResetPassword($resetPassword);

                return null;
            }
        }
        return null;
    }

    public function resetPassword(ResetPassword $resetPassword, $new_password) : ?String
    {

        if(!$this->dateIsValid($resetPassword->getCreatedAt())){
            return "Vous avez mis trop de temps pour changer votre mot de passe. Le liens à expiré.";
        }

        if($resetPassword->getState() == 1){
            return "Vous avez dejà modifié votre mot de passe.";
        }

        $this->userService->resetPassword($new_password, $resetPassword->getUser());

        $this->resetPasswordSuccessfully($resetPassword);

        return null;
    }
}