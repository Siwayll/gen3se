<?php

namespace Gen3se\Engine;

use Gen3se\Engine\Choice\Choice;

interface ChoiceProviderInterface
{
    public function get(string $choiceName): Choice;

    public function hasChoice(string $choiceName): bool;
}
