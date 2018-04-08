<?php declare(strict_types = 1);

namespace Gen3se\Engine\Choice\Option;

use Gen3se\Engine\Choice\Option;

interface CollectionInterface extends \Countable
{
    public function getTotalWeight(): int;
    public function findByPositionInStack(int $position): Option;
}
