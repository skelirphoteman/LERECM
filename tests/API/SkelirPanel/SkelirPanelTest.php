<?php

namespace App\Tests\API\SkelirPanel;

use App\Domain\User\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SkelirPanelTest extends WebTestCase
{

    private $index = "/reset/password";

    /**
     * Connexion à /reset/password
     * roles : IS_ANONYMOUS
     * response : successfully
     */
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('GET', $this->index);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}