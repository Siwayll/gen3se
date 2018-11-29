<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Features;

use Gen3se\Engine\Randomizer;

class ControlledRandomizer implements Randomizer
{
    private $values = [];
    private $cursor = 0;
    public function setReturnValue(int ...$values): void
    {
        $this->values = $values;
    }

    public function rollForRange(int $max, int $min = 0): int
    {
        if (!isset($this->values[$this->cursor])) {
            return \rand($min, $max);
        }

        return $this->values[$this->cursor++];
    }
}
