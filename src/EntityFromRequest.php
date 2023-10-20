<?php

namespace Vvvdev\ClassFromRequest;

use ReflectionClass;
use ReflectionMethod;

class EntityFromRequest
{
    private string $class;
    private mixed $entity;

    /**
     * @return mixed
     */
    public function getEntity(string $class, string $method = ''): mixed
    {
        $this->class = $class;
        if ($method === '__construct') {
            $this->handleByConstruct();
            return $this->entity;
        }

        $this->handle();

        return $this->entity;
    }

    private function getClass(): string
    {
        return $this->class;
    }

    /**
     * @throws \ReflectionException
     */
    private function handle()
    {
        $parsedData = $this->getData();

        $reflectionClass = new ReflectionClass($this->getClass());
        $parameters = $reflectionClass->getProperties();

        $entity = new ($this->getClass());

        foreach ($parameters as $parameter) {
            $paramType = $parameter->hasType() ? $parameter->getType()->getName() : 'mixed';

            if (!$parameter->isPublic()) continue;

            if ($paramType == "int") {
                $parameter->setValue($entity, (int)$parsedData[$parameter->getName()]);
            } else {
                $parameter->setValue($entity, $parsedData[$parameter->getName()]);
            }
        }

        $this->entity = $entity;
    }

    private function handleByConstruct(): void
    {
        $parsedData = $this->getData();

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

    public function getData(): array
    {
        $inputData = file_get_contents("php://input");
        parse_str($inputData, $parsedData);

        return $parsedData ?? [];
    }
}