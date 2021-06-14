<?php

namespace App\Tests\Controller\Client;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\Repository\ResetPasswordRepository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClientListTest extends WebTestCase
{

    private $index = "app/client/list";
    private $addClient = "app/client/add";

    /**
     * Connexion à app/client/list
     * roles : IS_ANONYMOUS
     * response : failed
     */
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', $this->index);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    /**
     * Connexion à app/client/list
     * roles : ROLE_COMPANY
     * response : succefully
     */
    public function testIndexConnected()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('kevin@test.fr');

        $client->loginUser($testUser);

        $crawler = $client->request('GET', $this->index);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}