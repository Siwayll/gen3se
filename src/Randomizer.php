<?php declare(strict_types = 1);

namespace Gen3se\Engine;

interface Randomizer
{
    public function rollForRange(int $max, int $min = 0): int;
}
