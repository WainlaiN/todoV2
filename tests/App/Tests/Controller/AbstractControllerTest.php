<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractControllerTest extends WebTestCase
{
    protected $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function loginWithAdmin(): void
    {
        $crawler = $this->client->request('GET', '/login');
        //$form = $crawler->selectButton('Se connecter')->form();

        //$form['email']->setValue('admin@gmail.com');
        //$form['password']->setValue('admin');

        //$this->client->submit($form);

        $this->client->submitForm('Se connecter', [
            'email' => 'admin@gmail.com',
            'password' => 'admin']);
    }

    public function loginWithUser(): void
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();

        $form['email']->setValue('user@gmail.com');
        $form['password']->setValue('user');

        $this->client->submit($form);
    }

}