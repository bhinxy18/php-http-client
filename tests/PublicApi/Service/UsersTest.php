<?php

namespace CoreDNA\PublicApi\Test;

use PHPUnit\Framework\TestCase;
use CoreDNA\PublicApi\Service\Users;
use CoreDNA\PublicApi\Model\User;
use CoreDNA\PublicApi\ServiceGateway;

class UsersTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
    
    /**
     * Tests
     */
    public function testHydratorAssignsDataCorrectly()
    {
        $mockGateway = \Mockery::mock(ServiceGateway::class)->makePartial();
        $mockGateway->shouldReceive('requestToken')->andReturn('test-token');

        $users = new Users($mockGateway);

        $userData = [
            'name' => 'John',
            'email' => 'j@test.com',
            'url' => 'https://a.com'
        ];
        $hydrator = $users->hydrate($userData);

        $this->assertInstanceOf(User::class, $hydrator);

        $this->assertEquals('John', $hydrator->getName());
    }

    public function testSerializeConvertsDataCorrectly()
    {
        $mockGateway = \Mockery::mock(ServiceGateway::class)->makePartial();
        $mockGateway->shouldReceive('requestToken')->andReturn('test-token');

        $users = new Users($mockGateway);

        $user = new User();
        $user->setName('John Doe');
        $user->setEmail('john@gmail.com');
        $user->setUrl('https://www.example.test');

        $serialised = $users->serialize($user);

        $this->assertEquals('{"name":"John Doe","email":"john@gmail.com","url":"https:\/\/www.example.test"}', $serialised);
    }
}
