<?php

namespace App\Tests\Controller\Security;
use App\Domain\User\Repository\UserRepository;
use App\Domain\Client\Repository\ClientRepository;
use App\Domain\User\Repository\ResetPasswordRepository;
use App\Http\Form\AddClientType;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Form\Test\TypeTestCase;


class ClientEditTest extends WebTestCase
{
    private $index = "app/client/edit/";

    private TypeTestCase $typecase;

    /**
     * Connexion à app/client/edit
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
     * Connexion à app/client/edit
     * roles : ROLE_COMPANY
     * response : failed
     */
    public function testIndexConnectedwithoutId()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('jean@test.fr');

        $client->loginUser($testUser);

        $crawler = $client->request('GET', $this->index);

        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    /**
     * Connexion à app/client/edit
     * roles : ROLE_COMPANY
     * response : failed
     */
    public function testIndexConnectedClientNotFound()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('jean@test.fr');

        $client->loginUser($testUser);

        $crawler = $client->request('GET', $this->index . '151541516541635');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * Connexion à app/client/edit
     * roles : ROLE_COMPANY
     * response : succefully
     */
    public function testIndexConnectedClient()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('kevin@test.fr');

        $clientRepository = static::$container->get(ClientRepository::class);
        $testClient = $clientRepository->findOneBy(["name" => "Julien"]);

        $client->loginUser($testUser);

        $crawler = $client->request('GET', $this->index . $testClient->getId());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}