<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Provider;

trait Result
{
    protected function createMockResult()
    {
        $mock = $this->newMockInstance(\Gen3se\Engine\Result::class);

        return $mock;
    }
}
