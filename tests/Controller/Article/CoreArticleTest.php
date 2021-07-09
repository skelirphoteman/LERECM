<?php

namespace App\Tests\Controller\Article;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Domain\User\Repository\UserRepository;

class CoreArticleTest extends WebTestCase
{
    private $path = "app";

    /**
     * Connexion à app
     * roles : ROLE_COMPANY
     * response : succefully
     */
    public function testIndexConnected()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('jean@test.fr');

        $client->loginUser($testUser);

        $crawler = $client->request('GET', $this->path);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('.card-body strong', 'Test');
    }
}