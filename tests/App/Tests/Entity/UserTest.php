<?php


namespace App\Tests\Entity;


use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{

    private function getEntity(): User
    {
        return (new User())
            ->setEmail("test@test.com")
            ->setRoles(['ROLE_USER'])
            ->setPassword('password');
    }

    private function addTaskToEntity(): User
    {
        $user = $this->getEntity();
        $task = (new Task())
            ->setTitle("title")
            ->setContent("content");

        return $user->addTask($task);
    }

    private function getDatabaseUser(): User
    {
        self::bootKernel();
        $userRepository = static::$container->get(UserRepository::class);
        $basicUser = $userRepository->findOneByEmail('user@gmail.com');

        return $basicUser;
    }

    private function assertHasErrors(User $user, int $number = 0)
    {
        self::bootKernel();
        $error = self::$container->get('validator')->validate($user);
        $this->assertCount($number, $error);

    }

    public function testGetIdUser()
    {
        $user = $this->getDatabaseUser();
        $this->assertIsInt($user->getId());
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


    public function testValidPasswordUser()
    {
        $this->assertHasErrors($this->getEntity()->setPassword("password"), 0);
    }

    public function testInValidPasswordUser()
    {
        $this->assertHasErrors($this->getEntity()->setPassword("pass"), 1);
    }

    public function testGetPasswordUser()
    {
        $this->assertEquals("password", $this->getEntity()->getPassword());
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

    public function testAddTaskUser()
    {
        $task = new Task();
        $this->assertHasErrors(
            $this->getEntity()
                ->addTask($task),
            0
        );

    }

    public function testGetTaskUser()
    {
        $user = $this->addTaskToEntity();
        /** @var Task $task */
        $task = $user->getTasks()[0];
        $this->assertEquals("title", $task->getTitle());
        $this->assertEquals("content", $task->getContent());


    }

    public function testRemoveTaskUser()
    {
        $task = new Task();
        $this->getEntity()->addTask($task);
        $task->setUser($this->getEntity());
        $this->assertHasErrors($this->getEntity()->removeTask($task), 0);
        $task->setUser(null);
        $this->assertEquals(null, $task->getUser());

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
        $this->assertCount("3", $tasks);
    }

    public function testRemoveAssignedTaskUser()
    {
        $user = $this->getDatabaseUser();
        $tasks = $user->getAssignedTasks();
        $user->removeAssignedTask($tasks[0]);
        $this->assertCount("2", $tasks);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testEraseCredentials()
    {
        $this->doesNotPerformAssertions();
    }



}