<?php

namespace Gen3se\Engine;


interface ScenarioInterface extends \Countable
{
    /**
     * @return bool
     */
    public function hasNext(): bool;

    /**
     * Return the name of the next choice to resolve
     * @return string
     */
    public function next(): string;
}