<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Provider\Choice;

trait Collection
{
    protected function createMockOptionCollection(?int $count = null)
    {
        $mock = $this->newMockInstance(\Gen3se\Engine\Choice\Option\CollectionInterface::class);
        $mock->getMockController()->count = $count;

        return $mock;
    }
}
