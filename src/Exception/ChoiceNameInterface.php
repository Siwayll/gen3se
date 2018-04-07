<?php

namespace Gen3se\Engine\Exception;

/**
 * Exceptions with this Interface will receive the current __Choice__ name's
 */
interface ChoiceNameInterface
{
    public function setChoiceName(string $choiceName): void;
}
