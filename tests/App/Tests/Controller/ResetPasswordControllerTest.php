<?php


namespace App\Tests\Controller;


use App\Entity\User;
use App\Repository\UserRepository;

class ResetPasswordControllerTest extends AbstractControllerTest
{
    /** @var UserRepository */
    protected $userRepository;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->userRepository = self::$container->get(UserRepository::class);
    }

    public function testRequestResetPassword()
    {
        $crawler = $this->client->request('GET', '/reset-password');

        $this->assertResponseIsSuccessful();
        $this->assertContains('Réinitialiser votre mot de passe', $crawler->filter('h1')->text());

        $form = $crawler->selectButton('Envoyer')->form();
        $form['reset_password_request_form[email]'] = 'user@gmail.com';

        $this->client->submit($form);

        $user = $this->userRepository->findOneBy(['email' => 'user@gmail.com']);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($user->getEmail(),'user@gmail.com' );
    }

    public function testInvalidRequestResetPassword()
    {
        $crawler = $this->client->request('GET', '/reset-password');

        $this->assertResponseIsSuccessful();
        $this->assertContains('Réinitialiser votre mot de passe', $crawler->filter('h1')->text());

        $form = $crawler->selectButton('Envoyer')->form();
        $form['reset_password_request_form[email]'] = 'user2@gmail.com';

        $this->client->submit($form);

        $user = $this->userRepository->findOneBy(['email' => 'user2@gmail.com']);

        $this->assertEmpty($user);

        $this->assertResponseRedirects("/reset-password");
    }


}