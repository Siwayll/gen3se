<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Core\Provider\Choice;

use Gen3se\Engine\Specs\Units\Core\Provider\Choice\Option;

trait Collection
{
    use Option;
    protected function createMockOptionCollection(
        ?int $count = 0,
        ?int $totalWeight = 0,
        ?\Gen3se\Engine\Choice\Option $result = null
    ) {
        $result = $result ?? $this->createMockOption();
        $mock = $this->newMockInstance(\Gen3se\Engine\Choice\Option\CollectionInterface::class);
        $mock->getMockController()->count = $count;
        $mock->getMockController()->getTotalWeight = $totalWeight;
        $mock->getMockController()->findByPositionInStack = $result;

        return $mock;
    }
}
