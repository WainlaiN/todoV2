<?php

namespace App\Tests\Controller;


use App\Repository\TaskRepository;

class TaskControllerTest extends AbstractControllerTest
{
    /** @var TaskRepository */
    protected $taskRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->taskRepository = self::$container->get(TaskRepository::class);
    }

    public function testInvalidAccess()
    {
        $this->client->request('GET', '/task/all');
        $this->assertResponseRedirects("/login");
    }

    public function testIndexAllTasks()
    {
        $this->loginWithAdmin();
        $this->client->request('GET', '/task/all');
        $this->assertResponseIsSuccessful();
    }

    public function testIndexTodo()
    {
        $this->loginWithAdmin();
        $this->client->request('GET', '/task/todo');
        $this->assertResponseIsSuccessful();
    }

    public function testIndexDone()
    {
        $this->loginWithAdmin();
        $this->client->request('GET', '/task/done');
        $this->assertResponseIsSuccessful();
    }

    public function testIndexInProgress()
    {
        $this->loginWithAdmin();
        $this->client->request('GET', '/task/inprogress');
        $this->assertResponseIsSuccessful();
    }

    public function testAssignTask()
    {
        $this->loginWithUser();

        $crawler = $this->client->request('GET', '/tasks/assign/91');

        $crawler = $this->client->followRedirect();

        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());

        $taskToAssign = $this->taskRepository->findOneBy(['id' => 91]);

    }
}
