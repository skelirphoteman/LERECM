<?php

namespace App\DataFixtures;

use App\Domain\User\Entity\User;
use App\Domain\Company\Entity\Company;
use App\Domain\Client\Entity\Client;
use App\Domain\Subscription\Entity\Subscription;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;


class AdminFixtures extends Fixture implements FixtureGroupInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        //ADMIN
        $user = new User();
        $user->setEmail('galoin.julien@gmail.com');

        $password = $this->encoder->encodePassword($user, 'pass_1234');
        $user->setPassword($password);

        $user->addRole('ROLE_COMPANY');
        $user->addRole('ROLE_ADMIN');

        $user->setSurname('Galoin');
        $user->setName('Julien');
        $user->setCreatedAt(new \DateTime('now'));
        $user->setState(0);
        $manager->persist($user);

        //Subscription
        $subscription = new Subscription();
        $subscription->setEndAt(new \DateTime('+1 Year'));

        $manager->persist($subscription);
        //Company
        $company = new Company();
        $company->setName('Skelir\'s Creation');
        $company->addUser($user);
        $company->setSubscription($subscription);

        $manager->persist($company);

        $manager->flush();
    }

     public static function getGroups(): array
     {
         return ['prod'];
     }
}
