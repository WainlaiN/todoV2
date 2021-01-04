<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{

    private $client;

    protected function setUp()
    {
        $this->client = self::createClient();
    }

    public function loginAdmin()
    {

        $userRepository = static::$container->get(UserRepository::class);
        $adminUser = $userRepository->findOneByEmail('admin@gmail.com');

        $this->client->loginUser($adminUser);
        return $this->client;
    }

    public function testIndexAllUsers()
    {
        $this->loginAdmin();
        $this->client->request('GET', '/admin/user');
        $this->assertResponseIsSuccessful();
    }

    public function testCreateAction()
    {
        $this->loginAdmin();

        $crawler = $this->client->request('GET', 'admin/users/create');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

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
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());
    }
}
