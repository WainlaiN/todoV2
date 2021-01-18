<?php


namespace App\Tests\Entity;


use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;

class UserTest extends AbstractEntityTest
{
    private function getDatabaseUser(): User
    {
        self::bootKernel();
        $userRepository = static::$container->get(UserRepository::class);
        $basicUser = $userRepository->findOneByEmail('user@gmail.com');

        return $basicUser;
    }

    public function testGetIdUser()
    {
        $user = $this->getDatabaseUser();
        $this->assertIsInt($user->getId());
    }

    public function testValidUser()
    {
        $this->assertUserHasErrors($this->getEntityUser(), 0);

    }

    public function testInvalidEmailUser()
    {
        $this->assertUserHasErrors($this->getEntityUser()->setEmail("testtest.com"), 1);
    }

    public function testValidEmailUser()
    {
        $emailToTest = $this->getEntityUser()->getEmail();
        $this->assertSame($emailToTest, filter_var($emailToTest, FILTER_VALIDATE_EMAIL));
    }


    public function testValidPasswordUser()
    {
        $this->assertUserHasErrors($this->getEntityUser()->setPassword("password"), 0);
    }

    public function testInValidPasswordUser()
    {
        $this->assertUserHasErrors($this->getEntityUser()->setPassword("pass"), 1);
    }

    public function testGetPasswordUser()
    {
        $this->assertEquals("password", $this->getEntityUser()->getPassword());
    }

    public function testInvalidUsernameUser()
    {
        $this->assertUserHasErrors($this->getEntityUser()->setEmail("testtest.com"), 1);
    }

    public function testValidUsernameUser()
    {
        $emailToTest = $this->getEntityUser()->getUsername();
        $this->assertSame($emailToTest, filter_var($emailToTest, FILTER_VALIDATE_EMAIL));
    }

    public function testValidRoleUser()
    {
        $this->assertSame(['ROLE_USER'], $this->getEntityUser()->getRoles());

    }

    public function testAddTaskUser()
    {
        $task = new Task();
        $this->assertUserHasErrors(
            $this->getEntityUser()
                ->addTask($task),
            0
        );

    }

    public function testGetTaskUser()
    {
        $user = $this->getEntityUser()->addTask($this->getEntityTask());
        /** @var Task $task */
        $task = $user->getTasks()[0];
        $this->assertEquals("titre", $task->getTitle());
        $this->assertEquals("contenu", $task->getContent());
    }

    public function testRemoveAssignedTaskUser()
    {
        $user = $this->getDatabaseUser();
        $tasks = $user->getAssignedTasks();
        $user->removeAssignedTask($tasks[0]);
        $this->assertCount("1", $tasks);
    }

    public function testAddAssignedTaskUser()
    {
        $user = new User();
        $task = new Task();
        $user->addAssignedTask($task);
        $this->assertEquals($user, $task->getAssignedTo());
    }

    public function testGetAssignedTasksUser()
    {
        $user = $this->getDatabaseUser();
        $tasks = $user->getAssignedTasks();
        $this->assertCount("2", $tasks);
    }

    public function testUpgradePassword()
    {
        $user = $this->getDatabaseUser();
        $newPassword = "new";

        $userRepository = static::$container->get(UserRepository::class);
        $userRepository->upgradePassword($user, $newPassword);

        $this->assertEquals($newPassword, $user->getPassword());
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testEraseCredentials()
    {
        $this->doesNotPerformAssertions();
    }



}