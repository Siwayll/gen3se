<?php

namespace Gen3se\Engine\Choice\Option;

use Gen3se\Engine\Choice\OptionInterface;

interface CollectionInterface
{
    public function __construct(OptionInterface ...$option);

    public function add(OptionInterface $option): self;
    public function get(string $optionName): OptionInterface;
    public function count(): int;
    public function getTotalWeight(): int;
    public function findByPositionInStack(int $position): OptionInterface;
}
