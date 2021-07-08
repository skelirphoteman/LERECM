<?php

namespace App\DataFixtures;

use App\Domain\User\Entity\User;
use App\Domain\Company\Entity\Company;
use App\Domain\Client\Entity\Client;
use App\Domain\Subscription\Entity\Subscription;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TestFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        //Utilisateur gentil
        $user = new User();
        $user->setEmail('jean@test.fr');

        $password = $this->encoder->encodePassword($user, 'pass_1234');
        $user->setPassword($password);

        $user->addRole('ROLE_COMPANY');

        $user->setSurname('Galoin');
        $user->setName('Jean');
        $user->setCreatedAt(new \DateTime('now'));
        $user->setState(0);
        $manager->persist($user);

        //Subscription
        $subscription = new Subscription();
        $subscription->setSubscriptionPanelId(12);
        $subscription->setEndAt(new \DateTime('+1 Year'));

        $manager->persist($subscription);
        //Company
        $company = new Company();
        $company->setName('GPC');
        $company->addUser($user);
        $company->setUserPanelId(46);
        $company->setSubscription($subscription);

        $manager->persist($company);

        //client
        $client = new Client($user);
        $client->setIsCompany(false);
        $client->setSurname('korp');
        $client->setName('Michel');
        $client->setCompany($company);
        $manager->persist($client);



        //Utilisateur MECHANT
        $kevin = new User();
        $kevin->setEmail('kevin@test.fr');

        $password = $this->encoder->encodePassword($kevin, 'pass_1234');
        $kevin->setPassword($password);

        $kevin->addRole('ROLE_COMPANY');

        $kevin->setSurname('Michel');
        $kevin->setName('Kevin');
        $kevin->setCreatedAt(new \DateTime('now'));
        $kevin->setState(0);
        $manager->persist($kevin);

        //Subscription
        $subscription = new Subscription();
        $subscription->setSubscriptionPanelId(13);
        $subscription->setEndAt(new \DateTime('+1 Year'));

        $manager->persist($subscription);
        //Company
        $company = new Company();
        $company->setName('KEVINCORP');
        $company->addUser($kevin);
        $company->setUserPanelId(47);
        $company->setSubscription($subscription);

        $manager->persist($company);

        //client
        $client = new Client($kevin);
        $client->setIsCompany(false);
        $client->setSurname('Galoin');
        $client->setName('Julien');

        $manager->persist($client);


        $manager->flush();
    }
}
