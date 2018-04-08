<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Provider;

use Gen3se\Engine\Choice\Option\CollectionInterface;

trait Choice
{
    protected function createMockChoice(
        ?string $name = null,
        ?CollectionInterface $collection = null
    ) {
        $name = $name ?? uniqid();

        $mock = new \mock\Gen3se\Engine\Choice();
        $mock->getMockController()->getName = $name;
        if ($collection !== null) {
            $mock->getMockController()->getOptionCollection = $collection;
        }

        return $mock;
    }
}
