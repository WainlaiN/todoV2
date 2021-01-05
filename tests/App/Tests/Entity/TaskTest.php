<?php


namespace App\Tests\Entity;


use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    public function getEntity(): Task
    {
        return (new Task())
            ->setTitle("titre")
            ->setContent("contenu");
    }

    public function getEntityWithUser(): Task
    {
        return $this->getEntity()->setUser(
            (new User())
                ->setEmail("test@test.com")
                ->setPassword("password")
                ->setRoles(['ROLE_USER'])
        );
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

    public function testInvalidBlankTitleTask()
    {
        $task = $this->getEntity()->setTitle("");
        $this->assertHasErrors($task, 1);
    }

    public function testValidTitleTask()
    {
        $title = $this->getEntity()->getTitle();
        $this->assertEquals($title, "titre");
    }

    public function testValidContentTask()
    {
        $content = $this->getEntity()->getContent();
        $this->assertEquals($content, "contenu");
    }

    public function testInvalidBlankContentTask()
    {
        $task = $this->getEntity()->setContent("");
        $this->assertHasErrors($task, 1);
    }

    public function testAssignedAtTask()
    {
        $this->assertHasErrors($this->getEntity()->setAssignedAt(new \DateTime()), 0);
    }

    public function testVIsDoneTask()
    {
        $task = $this->getEntity()->setIsDone(true);
        $this->assertEquals(true, $task->getIsDone());
    }

    public function testSetDateCreatedTask()
    {
        $this->assertHasErrors($this->getEntity()->setCreatedAt(new \DateTime()), 0);
    }

    public function testValidGetCreatedAtTask()
    {
        $this->getEntity()->setCreatedAt(new \DateTime());
        $date1 = $this->getEntity()->getCreatedAt();
        $date2 = new \DateTime();
        $this->assertEqualsWithDelta($date1, $date2, 5);
    }

    public function testValidGetUserTask()
    {
        $task = $this->getEntityWithUser();

        $this->assertEquals($task->getUser()->getEmail(), "test@test.com");
    }


}