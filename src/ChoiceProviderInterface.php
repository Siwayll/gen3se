<?php declare(strict_types = 1);

namespace Gen3se\Engine;

use Gen3se\Engine\Choice;

interface ChoiceProviderInterface
{
    public function get(string $choiceName): Choice;

    public function hasChoice(string $choiceName): bool;
}
