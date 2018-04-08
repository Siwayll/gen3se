<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Provider\Choice;

trait Option
{
    protected function createMockOption(?string $name = null, ?int $weight = null)
    {
        $name = $name ?? uniqid();
        $weight = $weight ?? 100;

        $mock = $this->newMockInstance(\Gen3se\Engine\Choice\Option::class);

        $mock->getMockController()->getName = $name;
        $mock->getMockController()->getWeight = $weight;

        return $mock;
    }
}
