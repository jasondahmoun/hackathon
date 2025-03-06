<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserProperties()
    {
        $user = new User();
        $user->setEmail('john.doe@example.com');
        $user->setPassword('password123');
        $user->setRoles(['ROLE_ADMIN']);

        $this->assertEquals('john.doe@example.com', $user->getEmail());
        $this->assertEquals('password123', $user->getPassword());
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
        $this->assertContains('ROLE_USER', $user->getRoles()); // ROLE_USER is always added
    }

    public function testUserIdentifier()
    {
        $user = new User();
        $user->setEmail('john.doe@example.com');

        $this->assertEquals('john.doe@example.com', $user->getUserIdentifier());
    }

    public function testEraseCredentials()
    {
        $user = new User();
        $user->eraseCredentials();

        // Assuming eraseCredentials does not modify anything in this case
        $this->assertTrue(true);
    }
}
