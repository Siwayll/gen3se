<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Core\Provider;

use Gen3se\Engine\Choice as ChoiceMock;
use Gen3se\Engine\Choice\Data;
use Gen3se\Engine\Choice\Option\CollectionInterface;
use Gen3se\Engine\Choice\Resolved;

trait Choice
{
    protected function createMockChoice(
        ?string $name = null,
        ?CollectionInterface $collection = null,
        ?Data ...$data
    ) {
        $mock = $this->newMockInstance(ChoiceMock::class);
        $mock = $this->hydrateChoiceMock($mock, $name, $collection, ...$data);

        return $mock;
    }

    protected function createNewMockOfResolvedChoice(
        ?string $name = null,
        ?CollectionInterface $collection = null,
        ?Data ...$data
    ) {
        $mock = $this->newMockInstance(Resolved::class);
        $mock = $this->hydrateChoiceMock($mock, $name, $collection, ...$data);

        return $mock;
    }

    private function hydrateChoiceMock(
        $choice,
        ?string $name = null,
        ?CollectionInterface $collection = null,
        ?Data ...$data
    ) {
        $name = $name ?? \uniqid();
        $choice->getMockController()->getName = $name;
        $choice->getMockController()->findData = $data;
        if ($collection !== null) {
            $choice->getMockController()->getOptionCollection = $collection;
        }

        return $choice;
    }
}
