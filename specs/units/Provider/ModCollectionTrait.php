<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Core\Provider;

use Gen3se\Engine\Mod\ModInterface;
use Gen3se\Engine\Mod\StepableInterface;

trait ModCollectionTrait
{
    protected function createMockMod()
    {
        $mock = $this->newMockInstance(ModInterface::class);
        return $mock;
    }

    protected function createMockModStepable(string $stepName)
    {
        $mock = $this->newMockInstance(StepableInterface::class);
        $mock->getMockController()->isUpForStep = function ($name) use ($stepName) {
            if ($name === $stepName) {
                return true;
            }
            return false;
        };
        return $mock;
    }
}
