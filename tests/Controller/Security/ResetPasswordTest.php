<?php

namespace App\Tests\Controller\Security;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\Repository\ResetPasswordRepository;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ResetPasswordTest extends WebTestCase
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

    /**
     * Connexion à /reset/password
     * roles : IS_AUTHENTICATED_FULLY
     * response : failed
     */
    public function testIndexAuthentificatedFully()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('jean@test.fr');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', $this->index);

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    /**
     * Connexion à /reset/password
     * roles : IS_ANONYMOUS
     * Methode : POST
     * response : successfully
     */
    public function testIndexPostEmail()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', $this->index, ['email' => 'jean@test.fr']);
         // Attention à bien récupérer le crawler mis à jour

        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }

    /**
     * Connexion à /reset/password
     * roles : IS_ANONYMOUS
     * Methode : POST
     * response : failed
     */
    public function testIndexPostEmailTooMuchTentative()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', $this->index, ['email' => 'jean@test.fr']);
        // Attention à bien récupérer le crawler mis à jour

        $this->assertSame(1, $crawler->filter('div.alert.alert-danger')->count());
    }

    /**
     * Connexion à /reset/password
     * roles : IS_ANONYMOUS
     * Methode : POST
     * response : failed
     */
    public function testIndexPostEmailNotFound()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', $this->index, ['email' => 'crash@test.fr']);
        // Attention à bien récupérer le crawler mis à jour

        $this->assertSame(1, $crawler->filter('div.alert.alert-danger')->count());
    }

    /**
     * Connexion à /reset/password
     * roles : IS_ANONYMOUS
     * response : failed
     */
    public function testIndexTokenFailed()
    {
        $client = static::createClient();

        $client->request('GET', $this->index . '/impossible');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * Connexion à /reset/password
     * roles : IS_ANONYMOUS
     * response : successfuly
     */
    public function testIndexTokenSuccessfuly()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('jean@test.fr');

        $userRepository = static::$container->get(ResetPasswordRepository::class);
        $testResetPassword = $userRepository->findOneBy(['user' => $testUser]);


        $client->request('GET', $this->index . '/' . $testResetPassword->getToken());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * Connexion à /reset/password
     * roles : IS_ANONYMOUS
     * Methode : POST
     * response : failed
     */
    public function testIndexTokenResetPasswordFailed()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('jean@test.fr');

        $userRepository = static::$container->get(ResetPasswordRepository::class);
        $testResetPassword = $userRepository->findOneBy(['user' => $testUser]);


        $crawler = $client->request('POST', $this->index . '/' . $testResetPassword->getToken(), [
            'password' => 'test',
            'password_second' => 'test1234'
        ]);


        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('div.alert.alert-danger')->count());

    }

    /**
     * Connexion à /reset/password
     * roles : IS_ANONYMOUS
     * Methode : POST
     * response : successfuly
     */
    public function testIndexTokenResetPasswordSuccessfuly()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('jean@test.fr');

        $userRepository = static::$container->get(ResetPasswordRepository::class);
        $testResetPassword = $userRepository->findOneBy(['user' => $testUser]);


        $client->request('POST', $this->index . '/' . $testResetPassword->getToken(), [
            'password' => 'test',
            'password_second' => 'test'
        ]);

        $client->followRedirects();

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    /**
     * Connexion à /reset/password
     * roles : IS_ANONYMOUS
     * Methode : POST
     * response : successfuly
     */
    public function testIndexConnexionWithNewPassword()
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $client->submitForm('submit', [
                       'email' => 'jean@test.fr',
                        'password' => 'test'
        ]);

        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }


    /**
     * Connexion à /reset/password
     * roles : IS_ANONYMOUS
     * Methode : POST
     * response : failed
     */
    public function testIndexTokenResetPasswordFailedRepeatSuccessfully()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('jean@test.fr');

        $userRepository = static::$container->get(ResetPasswordRepository::class);
        $testResetPassword = $userRepository->findOneBy(['user' => $testUser]);


        $crawler = $client->request('POST', $this->index . '/' . $testResetPassword->getToken(), [
            'password' => 'test',
            'password_second' => 'test'
        ]);


        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('div.alert.alert-danger')->count());
    }


}