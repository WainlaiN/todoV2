<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(userPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $users = [];

        // create Admin
        $user = new User();
        $user->setEmail("admin@gmail.com")
            ->setPassword($this->encoder->encodePassword($user, "admin"))
            ->setRoles(['ROLE_ADMIN']);

        //add one task to admin for test
        $task = new Task();
        $task->setTitle($faker->jobTitle)
            ->setContent($faker->sentence($nbWords = 15, $variableNbWords = true))
            ->setIsDone(false)
            ->setCreatedAt($faker->dateTimeBetween('-1 years', 'now'));
        $task->setUser($user);

        $manager->persist($user);
        $users[] = $user;

        // create Normal User for test
        $user = new User();
        $user->setEmail("user@gmail.com")
            ->setPassword($this->encoder->encodePassword($user, "user"))
            ->setRoles(['ROLE_USER']);

        //add one task to user for test
        $task = new Task();
        $task->setTitle($faker->jobTitle)
            ->setContent($faker->sentence($nbWords = 15, $variableNbWords = true))
            ->setIsDone(false)
            ->setCreatedAt($faker->dateTimeBetween('-1 years', 'now'));
        $task->setUser($user);

        $manager->persist($user);
        $users[] = $user;

        // create anonymous user
        $user = new User();
        $user->setEmail("anonymous")
            ->setPassword($this->encoder->encodePassword($user, "anonymous"))
            ->setRoles(['ROLE_USER']);

        $manager->persist($user);
        $users[] = $user;

        //create users list
        for ($i = 1; $i <= 20; $i++) {
            $user = new User();
            $user->setEmail($faker->email)
                ->setPassword($this->encoder->encodePassword($user, $faker->password))
                ->setRoles(['ROLE_USER']);

            $manager->persist($user);
            $users[] = $user;
        }

        //create tasks todo
        for ($j = 1; $j <= 30; $j++) {
            $task = new Task();
            $task->setTitle($faker->jobTitle)
                ->setContent($faker->sentence($nbWords = 15, $variableNbWords = true))
                ->setIsDone(false)
                ->setCreatedAt($faker->dateTimeBetween('-1 years', 'now'))
                ->setUser($faker->randomElement($users));

            $manager->persist($task);
        }

        //create tasks in progress
        for ($l = 1; $l <= 30; $l++) {
            $dateCreated = $faker->dateTimeBetween('-1 years', 'now');
            $dateAssigned = $faker->dateTimeBetween($dateCreated, 'now');
            $task = new Task();
            $task->setTitle($faker->jobTitle)
                ->setContent($faker->sentence($nbWords = 15, $variableNbWords = true))
                ->setIsDone(false)
                ->setCreatedAt($dateCreated)
                ->setAssignedAt($dateAssigned)
                ->setUser($faker->randomElement($users))
                ->setAssignedTo($faker->randomElement($users));

            $manager->persist($task);
        }

        //create tasks done
        for ($k = 1; $k <= 30; $k++) {
            $dateCreated = $faker->dateTimeBetween('-1 years', 'now');
            $dateAssigned = $faker->dateTimeBetween($dateCreated, 'now');
            $task = new Task();
            $task->setTitle($faker->jobTitle)
                ->setContent($faker->sentence($nbWords = 15, $variableNbWords = true))
                ->setIsDone(true)
                ->setUser($faker->randomElement($users))
                ->setAssignedTo($faker->randomElement($users))
                ->setCreatedAt($dateCreated)
                ->setAssignedAt($dateAssigned);

            $manager->persist($task);
        }

        $manager->flush();
    }
}
