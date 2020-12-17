<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function login()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $basicUser = $userRepository->findOneByEmail('user@gmail.com');

        $client->loginUser($basicUser);
        return $client;
    }

    public function loginAdmin()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $adminUser = $userRepository->findOneByEmail('admin@gmail.com');

        $client->loginUser($adminUser);
        return $client;
    }

    public function testInvalidAccess()
    {
        $client = static::createClient();
        $client->request('GET', '/task/all');
        $this->assertResponseRedirects("/login");
    }

    public function testIndexAll()
    {
        $client = $this->login();
        $client->request('GET', '/task/all');
        $this->assertResponseIsSuccessful();
    }

    public function testIndexTodo()
    {
        $client = $this->login();
        $client->request('GET', '/task/todo');
        $this->assertResponseIsSuccessful();
    }

    public function testIndexDone()
    {
        $client = $this->login();
        $client->request('GET', '/task/done');
        $this->assertResponseIsSuccessful();
    }

    public function testIndexInProgress()
    {
        $client = $this->login();
        $client->request('GET', '/task/inprogress');
        $this->assertResponseIsSuccessful();
    }

    public function testAssignTask()
    {
        //$task = (new Task())
            //->setTitle("titre de test")
            //->setContent("contenu de test");

        $user = $this->createMock(User::class);
        $task = $this->createMock(Task::class);
        //$this->login();

        $user->addAssignedTask($task);

        $this->assertHasErrors($user, 0);

        //$user = static::createClient();
        //$user = $this->login();

    }

    public function assertHasErrors(User $user, int $number = 0)
    {
        self::bootKernel();
        $error = self::$container->get('validator')->validate($user);
        $this->assertCount($number, $error);

    }
}
