<?php

namespace Gen3se\Engine;

use Gen3se\Engine\Choice\Choice;

/**
 * Interface ChoiceProviderInterface
 * @package Gen3se\Engine
 */
interface ChoiceProviderInterface
{
    /**
     * @param string $choiceName
     * @return Choice
     */
    public function get(string $choiceName): Choice;

}
