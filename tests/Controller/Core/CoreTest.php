<?php

namespace App\Tests\Controller\Core;

use App\Domain\User\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CoreTest extends WebTestCase
{
    private $index = "/";

    /**
     * Connexion à /
     * roles : IS_ANONYMOUS
     * response : successfully
     */
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', $this->index);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * Connexion à /
     * roles : IS_AUTHENTICATED_FULLY
     * response : successfully
     */
    public function testIndexAuthentificatedFully()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('jean@test.fr');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        // test e.g. the profile page
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }

}