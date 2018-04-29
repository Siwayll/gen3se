<?php declare(strict_types = 1);

namespace Gen3se\Engine;

interface Scenario
{
//    public function hasNext(): bool;
//
//    /**
//     * Return the name of the next choice to resolve
//     */
//    public function next(): string;
//
//    /**
//     * Simply add a choice name at the end of the list
//     */
//    public function append(string $choiceName): Scenario;

    public function read(callable $runOnEachChoiceName): void;
}
