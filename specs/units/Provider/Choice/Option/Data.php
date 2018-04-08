<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Provider\Choice\Option;

trait Data
{
    protected function createMockOptionData(string $value = 'lorem ipsum')
    {
        $mock = $this->newMockInstance(\Gen3se\Engine\Choice\Option\Data::class);

        $mock->getMockController()->toArray = function () use ($value) {
            return ['text' => $value];
        };
        return $mock;
    }
}
