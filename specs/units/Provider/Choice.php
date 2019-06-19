<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Core\Provider;

use Gen3se\Engine\Choice as ChoiceMock;
use Gen3se\Engine\Choice\Data;
use Gen3se\Engine\Choice\Option\CollectionInterface;

trait Choice
{
    protected function createMockChoice(
        ?string $name = null,
        ?CollectionInterface $collection = null,
        ?Data ...$data
    ) {
        $name = $name ?? \uniqid();

        $mock = $this->newMockInstance(ChoiceMock::class);
        $mock->getMockController()->getName = $name;
        $mock->getMockController()->findData = $data;
        if ($collection !== null) {
            $mock->getMockController()->getOptionCollection = $collection;
        }


        return $mock;
    }
}
