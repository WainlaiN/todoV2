<?php


namespace App\Tests\Entity;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{

    public function getEntity(): User
    {
        return (new User())
            ->setEmail("test@test.com")
            ->setRoles(['ROLE_USER'])
            ->setPassword('password');
    }

    public function assertHasErrors(User $user, int $number = 0)
    {
        self::bootKernel();
        $error = self::$container->get('validator')->validate($user);
        $this->assertCount($number, $error);

    }

    public function testValidUser()
    {
        $this->assertHasErrors($this->getEntity(), 0);

    }

    public function testInvalidUser()
    {
        $this->assertHasErrors($this->getEntity()->setEmail("testtest.com"), 1);
    }


}