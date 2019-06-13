<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Core\Provider\Mod\Append;

trait OptionData
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
