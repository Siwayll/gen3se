<?php declare(strict_types = 1);

namespace Gen3se\Engine\Choice\Option\Data;

use Gen3se\Engine\Choice\Option\Data;

class Text implements Data
{
    const ARRAY_KEY = 'text';
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function toArray(): array
    {
        return [self::ARRAY_KEY => $this->value];
    }
}
