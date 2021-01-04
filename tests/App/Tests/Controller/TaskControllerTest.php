<?php

namespace App\Tests\Controller;


class TaskControllerTest extends AbstractControllerTest
{

    protected function setUp(): void
    {
        parent::setUp();
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
}
