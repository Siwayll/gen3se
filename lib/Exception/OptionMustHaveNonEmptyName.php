<?php

namespace Gen3se\Engine\Exception;

/**
 * class Exception
 */
class OptionMustHaveNonEmptyName extends \Exception
{
    public $message = 'Option must have a non-empty name';

    public $code = 400;

    /**
     * OptionMustHaveNonEmptyName constructor.
     */
    public function __construct() {
    }
}

