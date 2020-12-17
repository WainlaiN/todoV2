<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function login($client)
    {
        $userRepository = static::$container->get(UserRepository::class);
        $basicUser = $userRepository->findOneByEmail('user@gmail.com');

        $client->loginUser($basicUser);
    }

    public function loginAdmin($client)
    {
        $userRepository = static::$container->get(UserRepository::class);
        $adminUser = $userRepository->findOneByEmail('admin@gmail.com');

        $client->loginUser($adminUser);
    }

    public function testIndexAll()
    {
        $client = static::createClient();
        $this->login($client);

        $client->request('GET', '/task/all');

        $this->assertResponseIsSuccessful();
    }

    public function testIndexTodo()
    {
        $client = static::createClient();
        $this->login($client);

        $client->request('GET', '/task/todo');

        $this->assertResponseIsSuccessful();
    }

    public function testIndexDone()
    {
        $client = static::createClient();
        $this->login($client);

        $client->request('GET', '/task/done');

        $this->assertResponseIsSuccessful();
    }

    public function testIndexInProgress()
    {
        $client = static::createClient();
        $this->login($client);

        $client->request('GET', '/task/inprogress');

        $this->assertResponseIsSuccessful();
    }
}
