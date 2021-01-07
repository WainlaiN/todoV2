<?php


namespace App\Tests\Entity;


use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AbstractEntityTest extends KernelTestCase
{
    public function assertTaskHasErrors(Task $task, int $number = 0)
    {
        self::bootKernel();
        $error = self::$container->get('validator')->validate($task);
        $this->assertCount($number, $error);
    }

    public function assertUserHasErrors(User $user, int $number = 0)
    {
        self::bootKernel();
        $error = self::$container->get('validator')->validate($user);
        $this->assertCount($number, $error);
    }

    public function getEntityTask(): Task
    {
        return (new Task())
            ->setTitle("titre")
            ->setContent("contenu");
    }

    public function getEntityUser(): User
    {
        return (new User())
            ->setEmail("test@test.com")
            ->setRoles(['ROLE_USER'])
            ->setPassword('password');
    }

}