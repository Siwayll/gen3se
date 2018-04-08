<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Provider\Choice;

trait Option
{
    protected function createMockOption(string $name, int $weight)
    {
        $mock = $this->newMockInstance(\Gen3se\Engine\Choice\OptionInterface::class);

        $mock->getMockController()->getName = $name;
        $mock->getMockController()->getWeight = $weight;

        return $mock;
    }
}
