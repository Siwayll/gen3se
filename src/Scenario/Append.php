<?php declare(strict_types = 1);

namespace Gen3se\Engine\Scenario;

use Gen3se\Engine\Mod\Append\DataInterface;

class Append implements DataInterface
{
    protected $choices = [];

    public function __construct(string ...$choiceName)
    {
        foreach ($choiceName as $choice) {
            $this->add($choice);
        }
    }

    /**
     * Add a choiceName in the list to append in the scenario
     */
    protected function add(string $choiceName)
    {
        $this->choices[] = $choiceName;
    }

    public function toArray(): array
    {
        return [];
    }

    public function eachChoice()
    {
        foreach ($this->choices as $choice) {
            yield $choice;
        }
    }
}
