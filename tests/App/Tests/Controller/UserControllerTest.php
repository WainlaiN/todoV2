<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\ResetPasswordRequestRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;

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

        $this->assertResponseIsSuccessful();

        //test if user exist in repo
        $user = $this->userRepository->findOneBy(['email' => 'admin2@gmail.com']);
        self::assertInstanceOf(User::class, $user);
        self::assertEquals('admin2@gmail.com', $user->getEmail());
        self::assertEquals('ROLE_ADMIN', $user->getRoles()[0]);
    }
}
