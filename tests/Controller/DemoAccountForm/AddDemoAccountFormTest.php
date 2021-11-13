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
            'name' => 'Galoin Maxime',
            'email' => 'galoin.maxime@gmail.com',
            'phone' => '0760356985',
            'city' => 'Marseille',
            'find_by' => 'facebook',
            'informations' => 'test'
        ]);


        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}