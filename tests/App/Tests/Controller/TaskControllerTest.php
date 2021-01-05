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

        $this->client->request('POST', '/task/assign/91');

        $taskToAssign = $this->taskRepository->findOneBy(['id' => 91]);

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

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());

    }

    public function testEditTask()
    {
        $this->loginWithAdmin();
        $crawler = $this->client->request('GET', 'tasks/95/edit');
        $this->assertResponseIsSuccessful();

        $this->assertContains('Éditer une tâche', $crawler->filter('h2')->text());

        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'Etancheur2';
        $form['task[content]'] = 'Contenu2';
        $form['task[assignedTo]'] = '23';

        $this->client->submit($form);

        $task = $this->taskRepository->findOneBy(['id' => '95']);
        //dd($task);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Etancheur', $task->getTitle());
        $this->assertEquals('Contenu2', $task->getContent());

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('div.alert-success')->count());

    }
}
