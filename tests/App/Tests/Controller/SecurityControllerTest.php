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

    public function testInvalidLogin()
    {
        $crawler = $this->client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Se connecter')->form();

        $form['email']->setValue('test@gmail.com');
        $form['password']->setValue('test');

        $this->client->submit($form);

        $crawler = $this->client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertEquals(1, $crawler->filter('div.alert-danger')->count());
    }

    public function testLogout()
    {
        $this->loginWithUser();

        $crawler = $this->client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $link = $crawler
            ->filter('a:contains("Deconnexion")')
            ->link();

        $this->client->click($link);

        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
    }

}