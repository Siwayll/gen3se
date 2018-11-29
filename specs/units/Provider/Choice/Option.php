<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Core\Provider\Choice;

use Gen3se\Engine\Choice\Option\Data;

trait Option
{

    protected function createMockOption(
        ?string $name = null,
        ?int $weight = null,
        ?array $data = null
    ) {
        $name = $name ?? \uniqid();
        $weight = $weight ?? 100;

        $data = $data ?? [];

        $mock = $this->newMockInstance(\Gen3se\Engine\Choice\Option::class);

//        $mock->getMockController()->getName = $name;
//        $mock->getMockController()->getWeight = $weight;
        $mock->getMockController()->dataToArray = $data;

        return $mock;
    }
}
