<?php

use PHPUnit\Framework\TestCase;
use Vvvdev\ClassFromRequest\EntityFromRequest;

class EntityFirstFromRequestTest extends TestCase
{
    public function testGetEntityWithValidData()
    {

        // Create a mock of EntityFromRequest class, mock only the getData method
        $entityFromRequestMock = $this->getMockBuilder(EntityFromRequest::class)
            ->onlyMethods(['handle', 'getData'])
            ->getMock();

        // Define the input data you want to mock
        $inputData = ['id' => 123, 'name' => 'name', 'description' => 'description'];

        // Mock the getData method to return your input data
        $entityFromRequestMock->expects($this->once())
            ->method('getData')
            ->willReturn($inputData);

        $entityFromRequest = $entityFromRequestMock->getEntity(EntityFirst::class);

        // Assert that the result is an instance of the specified class
        $this->assertEquals($inputData['name'], $entityFromRequest->name);
        $this->assertEquals($inputData['description'], $entityFromRequest->description);
    }

    public function testGetEntityConstructWithValidData()
    {

        // Create a mock of EntityFromRequest class, mock only the getData method
        $entityFromRequestMock = $this->getMockBuilder(EntityFromRequest::class)
            ->onlyMethods(['handle', 'getData'])
            ->getMock();

        // Define the input data you want to mock
        $inputData = ['id' => 123, 'name' => 'name', 'description' => 'description'];

        // Mock the getData method to return your input data
        $entityFromRequestMock->expects($this->once())
            ->method('getData')
            ->willReturn($inputData);

        $entityFromRequest = $entityFromRequestMock->getEntity(EntitySecond::class, '__construct');

        // Assert that the result is an instance of the specified class
        $this->assertEquals($inputData['name'], $entityFromRequest->name);
        $this->assertEquals($inputData['description'], $entityFromRequest->description);
    }
}

class EntityFirst {
    private int $id;
    public string $name;
    public string $description;
}


class EntitySecond {
    private int $id;
    public string $name;
    public string $description;

    public function __construct(string $name, string $description)
    {
        $this->name = $name;
        $this->description = $description;
    }
}
