<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Provider;

trait OptionTrait
{
    protected function createMockOption(
        ?string $name = null,
        ?int $weight = null,
        ?array $data = null
    ) {
        $name = $name ?? uniqid();
        $weight = $weight ?? rand(10, 300);
        $data = $data ?? [];

        $mock = new \mock\Gen3se\Engine\Choice\Option();
        $mock->getMockController()->getName = $name;
        $mock->getMockController()->getWeight = $weight;
        $mock->getMockController()->dataToArray = $data;

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
