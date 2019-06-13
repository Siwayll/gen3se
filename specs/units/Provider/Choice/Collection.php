<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Core\Provider\Choice;

trait Collection
{
    protected function createMockOptionCollection(
        ?int $count = null,
        ?int $totalWeight = null,
        ?\Gen3se\Engine\Choice\Option $result = null
    ) {
        $mock = $this->newMockInstance(\Gen3se\Engine\Choice\Option\CollectionInterface::class);
        $mock->getMockController()->count = $count;
        $mock->getMockController()->getTotalWeight = $totalWeight;
        $mock->getMockController()->findByPositionInStack = $result;

        return $mock;
    }
}
