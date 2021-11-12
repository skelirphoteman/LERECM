<?php

namespace App\Tests\Controller\DemoAccountForm;

use App\Domain\User\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddDemoAccountTestTest extends WebTestCase
{
    private $index = "/demoAccountForm";

    /**
     * Connexion à /demoAccountForm
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
     * Connexion à /demoAccountForm
     * roles : IS_ANONYMOUS
     * response : successfully
     */
    public function testPushForm()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', $this->index, [
            'password' => 'test',
            'password_second' => 'test1234'
        ]);


        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('div.alert.alert-danger')->count());
    }

}