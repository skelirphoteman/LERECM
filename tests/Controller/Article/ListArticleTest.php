<?php

namespace App\Tests\Controller\Article;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Domain\User\Repository\UserRepository;

class ListArticleTest extends WebTestCase
{
    private $path = "app/article/list";

    /**
     * Connexion à app/article/list
     * roles : IS_ANONYMOUS
     * response : failed
     */
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', $this->path);

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    /**
     * Connexion à app/article/list
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
        $this->assertSame(2, $crawler->filter('tr')->count());
    }
}