<?php


namespace App\Tests\Entity;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{

    public function testValidEntity () {

        $user = (New User())
            ->setEmail("test@test.com")
    }


}