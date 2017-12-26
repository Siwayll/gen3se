<?php

namespace Gen3se\Engine\Exception;

/**
 * class Exception
 */
class ChoiceDataValidatorCantHaveEmptyData extends \Exception
{
    public $message = 'Choice DataValidator can\'t have empty data';

    public $code = 400;

    /**
     * ChoiceDataCanNotBeEmpty constructor.
     */
    public function __construct()
    {
    }
}
