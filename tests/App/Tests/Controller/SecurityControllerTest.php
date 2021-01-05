<?php


namespace App\Tests\Controller;


class SecurityControllerTest extends AbstractControllerTest
{
    public function testLogin()
    {
        $this->loginWithUser();

        $this->client->request('GET', '/login');

        $this->assertResponseRedirects("/task/all");
    }

    public function testLogout()
    {
        $this->loginWithUser();

        $this->client->request('GET', '/logout');

        $this->client->followRedirect();

        $this->assertResponseIsSuccessful();

    }

}