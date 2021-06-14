<?php

namespace App\Tests\Controller\Security;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\Repository\ResetPasswordRepository;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClientAddTest extends WebTestCase
{
    private $index = "app/client/add";

    /**
     * Connexion à app/client/add
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
     * Connexion à app/client/add
     * roles : ROLE_COMPANY
     * response : succefully
     */
    public function testIndexConnected()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('jean@test.fr');

        $client->loginUser($testUser);

        $crawler = $client->request('GET', $this->index);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * Connexion à app/client/add
     * roles : ROLE_COMPANY
     * Methode : GET
     * response : successfuly
     */
    public function testIndexConnectedAdd()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('jean@test.fr');

        $client->loginUser($testUser);

        $crawler = $client->request('GET', $this->index);

        $form = $crawler->filter('input.btn-primary')->form([
            'add_client[name]' => 'First',
            'add_client[surname]' => 'Client',
            'add_client[is_company]' => "0"
        ]);

        $crawler = $client->submit($form);

        $crawler = $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }


}