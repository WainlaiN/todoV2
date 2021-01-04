<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AbstractControllerTest extends WebTestCase
{
    protected $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function loginWithAdmin(): void
    {
        $crawler = $this->client->request('GET', '/login');

        $buttonCrawlerMode = $crawler->filter('form');
        $form = $buttonCrawlerMode->form([
            'email' => 'admin@gmail.com',
            'password' => 'admin'
        ]);

        //$this->client->submit($form);

        $form['email']->setValue('admin@gmail.com');
        $form['password']->setValue('admin');

        dd($form);
    }

    /**public function loginWithUser(): void
    {
        $crawler = $this->client->request('GET', '/login');

        $buttonCrawlerMode = $crawler->filter('form');
        $form = $buttonCrawlerMode->form([
            'inputEmail' => 'user@gmail.com',
            'inputPassword' => 'user'
        ]);

        $this->client->submit($form);
    }**/


}