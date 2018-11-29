<?php declare(strict_types = 1);

namespace Gen3se\Engine\Choice\Option;

use Gen3se\Engine\Randomizer;

interface CollectionInterface extends \Countable
{
    public function selectAnOption(Randomizer $randomizer): void;
}
