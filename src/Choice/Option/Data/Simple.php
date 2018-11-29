<?php declare(strict_types = 1);

namespace Gen3se\Engine\Choice\Option\Data;

use Gen3se\Engine\Data;

class Simple implements Data
{
    /** @var mixed */
    private $value;

    /** @var string */
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
