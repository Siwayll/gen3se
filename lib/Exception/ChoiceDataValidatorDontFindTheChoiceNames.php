<?php

namespace Gen3se\Engine\Exception;

/**
 * class Exception
 */
class ChoiceDataValidatorDontFindTheChoiceNames extends \Exception
{
    public $message = 'DataValidator don\'t find the choice name\'s';

    public $code = 400;

    /**
     * ChoiceDataValidatorDontFindTheChoiceNames constructor.
     */
    public function __construct()
    {
    }
}
