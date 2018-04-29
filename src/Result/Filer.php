<?php declare(strict_types = 1);

namespace Gen3se\Engine\Result;

use Gen3se\Engine\Choice\Data;

interface Filer extends Data
{
    public function getDepth(): array;
}
