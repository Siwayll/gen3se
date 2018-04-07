<?php

namespace Gen3se\Engine\Specs\Units\Provider\Mod\Append;

trait DataTrait
{
    protected function createMockAppendData(string ...$choiceName)
    {
        $mock = new \mock\Gen3se\Engine\Mod\Append\DataInterface();

        $mock->getMockController()->toArray = [];
        $mock->getMockController()->eachChoice = function () use ($choiceName) {
            foreach ($choiceName as $name) {
                yield $name;
            }
        };
        return $mock;
    }
}
