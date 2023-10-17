<?php

namespace Vvvdev\ClassFromRequest;

use ReflectionMethod;

class EntityFromRequest
{
    private string $class;
    private mixed $entity;

    private function handle(): void
    {
        // getting all data, GET POST PUT PATCH
        $inputData = file_get_contents("php://input");
        parse_str($inputData, $parsedData);

        // getting the class constructor parameters and their types
        $reflectionMethod = new ReflectionMethod($this->getClass(), '__construct');
        $parameters = $reflectionMethod->getParameters();

        foreach ($parameters as $parameter) {
            $paramType = $parameter->hasType() ? $parameter->getType()->getName() : 'mixed';
            $data[$parameter->getName()] = $parsedData[$parameter->getName()] ?? '';

            if ($paramType == "int") {
                $data[$parameter->name] = (int)$data[$parameter->name];
            }
        }

        $entity = new ($this->getClass())(...$data);

        $this->entity = $entity;
    }

    /**
     * @return mixed
     */
    public function getEntity(string $class): mixed
    {
        $this->class = $class;
        $this->handle();

        return $this->entity;
    }

    private function getClass(): string
    {
        return $this->class;
    }
}