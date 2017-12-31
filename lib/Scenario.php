<?php
namespace Gen3se\Engine;

use Gen3se\Engine\Choice\Choice;
use Gen3se\Engine\Option\Option;

class Scenario implements ScenarioInterface
{
    private $list = [];

    private $current = null;

    public function __construct()
    {
    }

    /**
     * @return int|void
     */
    public function count()
    {
        return count($this->list);
    }

    /**
     * @param string $choiceName
     * @return Scenario
     */
    public function append(string $choiceName): ScenarioInterface
    {
        $this->list[] = $choiceName;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasNext(): bool
    {
        return !empty($this->list);
    }

    /**
     * @return string
     */
    public function next(): string
    {
        $this->current = array_shift($this->list);
        return $this->current;
    }
}
