<?php declare(strict_types = 1);

namespace Gen3se\Engine\Choice\Option\Data;

use Gen3se\Engine\Choice\Option\Data;

class Simple implements Data
{
    private $value;

    private $code;

    public function __construct(string $code, $value)
    {
        $this->code = $code;
        $this->value = $value;
    }

    public function toArray(): array
    {
        return [$this->code => $this->value];
    }
}
