<?php declare(strict_types = 1);

namespace Gen3se\Engine;

use Gen3se\Engine\Choice\Resolved;
use Gen3se\Engine\Scenario as Scenario;

interface Bible
{
    public function addChoice(string $choiceId, Choice $choice): void;
    /**  */
    public function play(Scenario $scenario): void;

    public function resolve(string $choiceName, Randomizer $randomize): void;
}
