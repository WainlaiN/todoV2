<?php

namespace App\Tests\Controller;


use App\Entity\Task;
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

        $this->client->request('POST', '/task/assign/1');

        $taskToAssign = $this->taskRepository->findOneBy(['id' => 1]);

        $this->assertEquals($taskToAssign->getAssignedTo(), "user@gmail.com");
    }

    public function testCreateTask()
    {
        $this->loginWithUser();
        $crawler = $this->client->request('GET', 'tasks/create');
        $this->assertResponseIsSuccessful();

        $this->assertContains('Ajouter une tâche', $crawler->filter('h2')->text());

        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'Tache de test';
        $form['task[content]'] = 'Contenu de test';

        $this->client->submit($form);

        $task = $this->taskRepository->findOneBy(['title' => 'Tache de test']);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Tache de test', $task->getTitle());
        $this->assertEquals('Contenu de test', $task->getContent());

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());

    }

    public function testEditTask()
    {
        $this->loginWithAdmin();
        $crawler = $this->client->request('GET', 'tasks/1/edit');
        $this->assertResponseIsSuccessful();

        $this->assertContains('Éditer une tâche', $crawler->filter('h2')->text());

        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'Solier-moquettiste';

        $this->client->submit($form);

        $this->assertResponseIsSuccessful();

        $task = $this->taskRepository->findOneBy(['id' => '1']);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Solier-moquettiste', $task->getTitle());
    }

    public function testCantEdit()
    {
        $this->loginWithUser();
        $this->client->request('GET', 'tasks/1/edit');

        $crawler = $this->client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertEquals(1, $crawler->filter('div.alert-danger')->count());
    }

    public function testToggleValidateTask()
    {
        $this->loginWithAdmin();

        $this->client->request('GET', '/tasks/33/toggle');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());

        $task = $this->taskRepository->findOneBy(['id' => 33]);
        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals(true, $task->getIsDone());

    }

    public function testToggleReOpenTask()
    {
        $this->loginWithAdmin();

        $this->client->request('GET', '/tasks/61/toggle');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());

        $task = $this->taskRepository->findOneBy(['id' => 61]);
        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals(false, $task->getIsDone());
        $this->assertEquals(null, $task->getAssignedTo());
    }

    public function testInvalidToggleTask()
    {
        $this->loginWithUser();

        $this->client->request('GET', '/tasks/31/toggle');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertEquals(1, $crawler->filter('div.alert-danger')->count());
    }

    public function testDeleteTask()
    {
        $this->loginWithAdmin();

        $this->client->request('DELETE', '/tasks/8/delete');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());

        $task = $this->taskRepository->findOneBy(['id' => 8]);
        $this->assertEmpty($task);
    }

    public function testInvalidDeleteTask()
    {
        $this->loginWithUser();

        $this->client->request('DELETE', '/tasks/1/delete');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $crawler = $this->client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertEquals(1, $crawler->filter('div.alert-danger')->count());
    }

}
