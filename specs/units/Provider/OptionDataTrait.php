<?php

namespace Gen3se\Engine\Specs\Units\Provider;

trait OptionDataTrait
{
    protected function createMockOptionData(string $value = 'lorem ipsum')
    {
        $mock = new \mock\Gen3se\Engine\Choice\Option\DataInterface();

        $mock->getMockController()->toArray = function () use ($value) {
            return ['text' => $value];
        };
        return $mock;
    }
}
