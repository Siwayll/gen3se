<?php declare(strict_types = 1);

namespace Gen3se\Engine\Choice\Option;

use Gen3se\Engine\Choice\Option;

interface CollectionInterface
{
    public function __construct(Option ...$option);

    public function add(Option $option): self;
    public function get(string $optionName): Option;
    public function count(): int;
    public function getTotalWeight(): int;
    public function findByPositionInStack(int $position): Option;
}
