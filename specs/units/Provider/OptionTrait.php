<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Provider;

trait OptionTrait
{
    protected function createMockOption(string $name, int $weight)
    {
        $mock = new \mock\Gen3se\Engine\Choice\Option();
        $mock->getMockController()->getName = $name;
        $mock->getMockController()->getWeight = $weight;
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
