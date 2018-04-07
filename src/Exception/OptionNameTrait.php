<?php

namespace Gen3se\Engine\Exception;

trait OptionNameTrait
{
    /**
     * futur name of the Option
     */
    protected $optionName = '{optionName}';

    /**
     * Set the name of the current option to simplify debug
     */
    final public function setOptionName(string $optionName): void
    {
        $this->optionName = $optionName;
    }
}
