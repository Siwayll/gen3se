<?php declare(strict_types = 1);

namespace Gen3se\Engine\Choice\Option;

interface DataInterface
{
    /**
     * Convert to array the data
     */
    public function toArray(): array;
}
