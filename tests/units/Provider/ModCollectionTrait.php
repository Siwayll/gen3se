<?php

namespace Gen3se\Engine\Tests\Units\Provider;

trait ModCollectionTrait
{
    protected function createMockMod()
    {
        $mock = new \mock\Gen3se\Engine\Mod\ModInterface();
        return $mock;
    }

    protected function createMockModStepable(string $stepName)
    {
        $mock = new \mock\Gen3se\Engine\Mod\StepableInterface();
        $mock->getMockController()->isUpForStep = function ($name) use ($stepName) {
            if ($name === $stepName) {
                return true;
            }
            return false;
        };
        return $mock;
    }
}
