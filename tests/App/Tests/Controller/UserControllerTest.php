<?php

namespace App\Tests\Controller;

use App\Controller\AbstractControllerTest;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends AbstractControllerTest
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testInvalidAccess()
    {
        $this->loginWithUser();
        $this->client->request('GET', '/admin/user');
        //ROLE_USER are denied to admin
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testIndexAllUsers()
    {
        $this->loginWithAdmin();
        $this->client->request('GET', '/admin/user');
        $this->assertResponseIsSuccessful();
    }

    public function testCreateAction()
    {
        $this->loginWithAdmin();
        $crawler = $this->client->request('GET', 'admin/users/create');
        $this->assertResponseIsSuccessful();
        //$this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        /**$crawler = $this->client->request('GET', '/users/create');
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertContains('Créer un utilisateur', $crawler->filter('h1')->text());

        $form = $crawler->selectButton('Se connecter')->form();

        $form['email']->setValue('admin@gmail.com');
        $form['roles']->setValue('ROLE_USER');

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'autre';
        $form['user[password][first]'] = 'autre';
        $form['user[password][second]'] = 'autre';
        $form['user[email]'] = 'autre@autre.org';
        $form['user[roles][0]']->tick();
        $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());**/
    }
}
