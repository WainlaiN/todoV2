<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\ResetPasswordRequestRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;

class UserControllerTest extends AbstractControllerTest
{
    //private EntityManager $em;

    /** @var UserRepository */
    protected $userRepository;

    /** @var ResetPasswordRequestRepository */
    protected $resetRepository;

    protected function setUp(): void
    {
        parent::setUp();
        //$this->em->getConnection()->beginTransaction();
        $this->userRepository = self::$container->get(UserRepository::class);
        $this->resetRepository = self::$container->get(ResetPasswordRequestRepository::class);
    }
    protected function tearDown(): void
    {
        parent::tearDown();
        //$this->em->getConnection()->rollback();
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

        $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $reset = $this->resetRepository->findOneBy(['user' => $user->getId()]);
        //$user = $this->userRepository->findOneBy(['email' => 'admin2@gmail.com']);
        $em->remove($reset);
        $em->flush();

        $em->remove($user);
        $em->flush();
    }
}
