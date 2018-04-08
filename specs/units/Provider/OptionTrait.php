<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Provider;

trait OptionTrait
{
    protected function createMockOption(string $name, int $weight)
    {
        $mock = new \mock\Gen3se\Engine\Choice\OptionInterface();
        $mock->getMockController()->getName = function () use ($name) {
            return $name;
        };
        $mock->getMockController()->getWeight = function () use ($weight) {
            return $weight;
        };
        return $mock;
    }

    protected function mockOptionProvider()
    {
        return [
            $this->createMockOption('one', 200),
            $this->createMockOption('two', 100)
        ];
    }
}
