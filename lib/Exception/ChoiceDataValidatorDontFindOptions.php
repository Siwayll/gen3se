<?php

namespace Gen3se\Engine\Exception;

/**
 * class Exception
 */
class ChoiceDataValidatorDontFindOptions extends \Exception
{
    public $choiceName;

    public $message = 'DataValidator don\'t find options for %s';

    public $code = 400;

    /**
     * ChoiceDataValidatorDontFindTheChoiceNames constructor.
     * @param string $choiceName
     */
    public function __construct(string $choiceName)
    {
        $this->choiceName = $choiceName;
    }
}
