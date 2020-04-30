<?php

namespace CoreDNA\PublicApi\Test;

use PHPUnit\Framework\TestCase;
use CoreDNA\PublicApi\Model\User;

class UserTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
    
    /**
     * Tests
     */
    public function testUserModelAssignsDataCorrectly()
    {
        $user = new User();
        $user->setName('John Doe');
        $user->setEmail('john@gmail.com');
        $user->setUrl('https://www.example.test');

        $this->assertInstanceOf(User::class, $user);

        $this->assertEquals('John Doe', $user->getName());
        $this->assertEquals('john@gmail.com', $user->getEmail());
        $this->assertEquals('https://www.example.test', $user->getUrl());
    }
}
