<?php

namespace App\DataFixtures;

use App\Domain\User\Entity\User;
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
        $manager->flush();
    }
}
