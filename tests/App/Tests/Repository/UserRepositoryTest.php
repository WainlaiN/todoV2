<?php

namespace App\Tests\Repository;


use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Repository\UserRepository;


class UserRepositoryTest extends KernelTestCase
{

    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testCount() {

        $users = self::$container->get(UserRepository::class)->count([]);
        $this->assertEquals(23, $users);
    }

}