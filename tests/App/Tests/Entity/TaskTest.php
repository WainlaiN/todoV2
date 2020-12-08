<?php


namespace App\Tests\Entity;


use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    public function getEntity(): Task
    {
        return (new Task())
            ->setTitle("titre")
            ->setContent("contenu");

    }

    public function assertHasErrors(Task $task, int $number = 0)
    {
        self::bootKernel();
        $error = self::$container->get('validator')->validate($task);
        $this->assertCount($number, $error);

    }

    public function testValidTask()
    {
        $this->assertHasErrors($this->getEntity(), 0);

    }

    public function testInvalidTask()
    {
        $task = $this->getEntity()->setTitle("")->setContent("");
        $this->assertHasErrors($task, 2);
    }

}