<?php


namespace App\Tests\Entity;


use App\Entity\Task;
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

    public function testInvalidEmailUser()
    {
        $this->assertHasErrors($this->getEntity()->setEmail("testtest.com"), 1);
    }

    public function testValidEmailUser()
    {
        $emailToTest = $this->getEntity()->getEmail();
        $this->assertSame($emailToTest, filter_var($emailToTest, FILTER_VALIDATE_EMAIL));
    }


    public function testValidPassword()
    {
        $this->assertHasErrors($this->getEntity()->setPassword("password"), 0);
    }

    public function testInValidPassword()
    {
        $this->assertHasErrors($this->getEntity()->setPassword("pass"), 1);
    }

    public function testInvalidUsernameUser()
    {
        $this->assertHasErrors($this->getEntity()->setEmail("testtest.com"), 1);
    }

    public function testValidUsernameUser()
    {
        $emailToTest = $this->getEntity()->getUsername();
        $this->assertSame($emailToTest, filter_var($emailToTest, FILTER_VALIDATE_EMAIL));
    }

    public function testValidRoleUser()
    {
        $this->assertSame(['ROLE_USER'], $this->getEntity()->getRoles());

    }

    public function testAddTask()
    {
        $task = new Task();
        $task->setContent("contenu de test")
            ->setTitle("titre de test");
        $this->assertHasErrors($this->getEntity()
            ->addTask($task), 0);

    }

    public function testAddInvalidTask()
    {
        $task = new Task();
        //$task->setContent("")
            //->setTitle("titre de test");
        $this->assertHasErrors($this->getEntity()
            ->addTask($task), 0);

    }


}