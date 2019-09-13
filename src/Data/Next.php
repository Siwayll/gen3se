<?php declare(strict_types = 1);

namespace Gen3se\Engine\Data;

use Gen3se\Engine\Data;

final class Next implements Data
{
    private const ARRAY_KEY = '_next';

    /** @var array */
    private $choiceNames = [];

    public function __construct(string ...$choiceName)
    {
        $this->choiceNames = $choiceName;
    }

    public function toArray(): array
    {
        return [self::ARRAY_KEY => $this->choiceNames];
    }
}
