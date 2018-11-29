<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Core\Provider;

trait Randomize
{
    protected function createNewMockOfRandomize(int $rollForRangeResult = 1): \Gen3se\Engine\Randomizer
    {
        $mock = $this->newMockInstance(\Gen3se\Engine\Randomizer::class);
        $mock->getMockController()->rollForRange = $rollForRangeResult;

        return $mock;
    }
}
