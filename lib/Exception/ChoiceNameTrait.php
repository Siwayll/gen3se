<?php

namespace Gen3se\Engine\Exception;

/**
 * Trait ChoiceNameTrait
 * @package Gen3se\Engine\Exception
 */
trait ChoiceNameTrait
{
    /**
     * @var string
     */
    protected $choiceName = '{choiceName}';

    /**
     * @param string $choiceName
     * @return self
     */
    final public function setChoiceName(string $choiceName): self
    {
        $this->choiceName = $choiceName;
        return $this;
    }
}
