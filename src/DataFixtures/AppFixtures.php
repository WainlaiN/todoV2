<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(userPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');


        // create Admin
        $user = new User();
        $user->setEmail("admin@gmail.com")
            ->setPassword($this->encoder->encodePassword($user, "admin"))
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);

        //create users & tasks
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setEmail($faker->email)
                ->setPassword($this->encoder->encodePassword($user, $faker->password))
                ->setRoles(['ROLE_USER']);

            for ($j = 1; $j <= 5; $j++) {
                $task = new Task();
                $task->setTitle($faker->jobTitle)
                    ->setContent($faker->sentence($nbWords = 6, $variableNbWords = true))
                    ->setIsDone($faker->boolean)
                    ->setCreatedAt($faker->dateTimeBetween('-2 years', 'now'))
                    ->setUser($user);

                $manager->persist($task);
            }
        }

        for ($k = 1; $k <= 10; $k++) {
            $task = new Task();
            $task->setTitle($faker->jobTitle)
                ->setContent($faker->sentence($nbWords = 6, $variableNbWords = true))
                ->setIsDone($faker->boolean)
                ->setCreatedAt($faker->dateTimeBetween('-2 years', 'now'));
        }



        $manager->flush();
    }
}
