<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;

class UserControllerTest extends AbstractControllerTest
{

    /** @var UserRepository */
    protected $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = self::$container->get(UserRepository::class);
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

        $this->assertContains('Créer un utilisateur', $crawler->filter('h1')->text());

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[email]'] = 'admin2@gmail.com';
        $form['user[roles]'] = 'ROLE_ADMIN';

        $this->client->submit($form);

        $user = $this->userRepository->findOneBy(['email' => 'admin2@gmail.com']);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('admin2@gmail.com', $user->getEmail());
        $this->assertEquals('ROLE_ADMIN', $user->getRoles()[0]);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());
    }

    public function testEditAction()
    {
        $this->loginWithAdmin();
        $crawler = $this->client->request('GET', 'admin/users/26/edit');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form();
        $form['user[email]'] = 'renault.georges2@orange.fr';
        $form['user[roles]'] = 'ROLE_ADMIN';

        $this->client->submit($form);

        $user = $this->userRepository->findOneBy(['email' => 'renault.georges2@orange.fr']);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('renault.georges2@orange.fr', $user->getEmail());
        $this->assertEquals('ROLE_ADMIN', $user->getRoles()[0]);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());

    }

}
