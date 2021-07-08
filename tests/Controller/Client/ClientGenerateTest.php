<?php


namespace App\Tests\Controller\Security;
use App\Domain\User\Repository\UserRepository;
use App\Domain\Client\Repository\ClientRepository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClientGenerateTest extends WebTestCase
{

    private $index = "app/client/generate/account";

    /**
     * Connexion à app/client/generate/account
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
     * Connexion à app/client/generate/account
     * roles : COMPANY
     * response : failed
     */
    public function testIndexClientFailed()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('jean@test.fr');

        $client->loginUser($testUser);

        $client->request('GET', $this->index);

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * Connexion à app/client/generate/account/{id}
     * roles : COMPANY
     * response : successfuly
     */
    public function testIndexClientSuccessfuly()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('jean@test.fr');

        $client->loginUser($testUser);

        $clientRepository = static::$container->get(ClientRepository::class);
        $testClient = $clientRepository->findOneBy(["name" => "Michel"]);



        $client->request('GET', $this->index . '/' . $testClient->getId());


        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }


}