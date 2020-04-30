<?php

namespace CoreDNA\PublicApi\Test;

use PHPUnit\Framework\TestCase;
use CoreDNA\PublicApi\ServiceGateway;

function file_get_contents($filename, $useIncludePath, $context)
{
    // TODO
    return ServiceGatewayTest::getValidJson();
}

class ServiceGatewayTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
    
    public function testAssigningOfEndpoint()
    {
        $mockGateway = \Mockery::mock(ServiceGateway::class);
        $mockGateway->shouldReceive('requestToken')->andReturn('test-token');
        $mockGateway->shouldReceive('getEndpoint')->andReturn('https://test.com');

        $this->assertEquals('https://test.com', $mockGateway->getEndpoint());
    }

    //TODO test API get and post request
    private function getValidJson()
    {
        return <<<'TAG'
[
    {
        "name": "John Doe",
        "email": "john@doe.com",
        "url": "https://www.john.doe"
    },
    {
        "name": "Jane Doe",
        "email": "jane@doe.com",
        "url": "https://www.jane.doe"
    }
]
TAG;
    }
}
