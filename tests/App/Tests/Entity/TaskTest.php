<?php


namespace App\Tests\Entity;


class TaskTest extends AbstractEntityTest
{
    public function testValidTask()
    {
        $this->assertTaskHasErrors($this->getEntityTask(), 0);
    }

    public function testInvalidBlankTitleTask()
    {
        $task = $this->getEntityTask()->setTitle("");
        $this->assertTaskHasErrors($task, 1);
    }

    public function testValidTitleTask()
    {
        $title = $this->getEntityTask()->getTitle();
        $this->assertEquals($title, "titre");
    }

    public function testValidContentTask()
    {
        $content = $this->getEntityTask()->getContent();
        $this->assertEquals($content, "contenu");
    }

    public function testInvalidBlankContentTask()
    {
        $task = $this->getEntityTask()->setContent("");
        $this->assertTaskHasErrors($task, 1);
    }

    public function testAssignedAtTask()
    {
        $this->assertTaskHasErrors($this->getEntityTask()->setAssignedAt(new \DateTime()), 0);
    }

    public function testIsDoneTask()
    {
        $task = $this->getEntityTask()->setIsDone(true);
        $this->assertEquals(true, $task->getIsDone());
    }

    public function testSetDateCreatedTask()
    {
        $this->assertTaskHasErrors($this->getEntityTask()->setCreatedAt(new \DateTime()), 0);
    }

    public function testValidGetCreatedAtTask()
    {
        $this->getEntityTask()->setCreatedAt(new \DateTime());
        $date1 = $this->getEntityTask()->getCreatedAt();
        $date2 = new \DateTime();
        $this->assertEqualsWithDelta($date1, $date2, 5);
    }

    public function testValidGetUserTask()
    {
        $task = $this->getEntityTask()->setUser($this->getEntityUser());

        $this->assertEquals($task->getUser()->getEmail(), "test@test.com");
    }


}