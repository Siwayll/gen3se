<?php

namespace Gen3se\Engine;

interface ScenarioInterface extends \Countable
{
    public function hasNext(): bool;

    /**
     * Return the name of the next choice to resolve
     */
    public function next(): string;

    /**
     * Simply add a choice name at the end of the list
     */
    public function append(string $choiceName): ScenarioInterface;
}
