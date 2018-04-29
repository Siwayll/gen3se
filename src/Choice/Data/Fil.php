<?php declare(strict_types = 1);

namespace Gen3se\Engine\Choice\Data;

use Gen3se\Engine\Result\Filer;

class Fil implements Filer
{
    private $offsets = [];
    public function __construct(string ...$offset)
    {
        $this->offsets = $offset;
    }

    public function getDepth(): array
    {
        return $this->offsets;
    }
}
