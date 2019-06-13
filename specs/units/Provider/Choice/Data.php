<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Core\Provider\Choice;

trait Data
{
    protected function createMockChoiceData()
    {
        $mock = $this->newMockInstance(\Gen3se\Engine\Choice\Data::class);

        return $mock;
    }
}
