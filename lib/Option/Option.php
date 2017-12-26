<?php

namespace Gen3se\Engine\Option;

use Gen3se\Engine\Exception\OptionMustHaveNonEmptyName;

class Option
{
    private $name;

    private $weight;

    /**
     * Option constructor.
     * @param string $name
     * @param int $weight
     * @throws OptionMustHaveNonEmptyName
     */
    public function __construct(string $name, int $weight)
    {
        if (empty($name)) {
            throw new OptionMustHaveNonEmptyName();
        }
        $this->name = $name;
        $this->weight = $weight;
    }
}
