<?php

namespace App\Tests\Controller;

use App\Controller\AbstractControllerTest;
use App\Repository\UserRepository;

class TaskControllerTest extends AbstractControllerTest
{

    protected function setUp(): void
    {
        parent::setUp();
    }


    public function testInvalidAccess()
    {
        $client = static::createClient();
        $client->request('GET', '/task/all');
        $this->assertResponseRedirects("/login");
    }

    public function testIndexAllTasks()
    {
        $this->loginWithUser();
        $this->client->request('GET', '/task/all');
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



}
