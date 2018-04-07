<?php

namespace Gen3se\Engine\Exception\Option;

/**
 * class Exception
 */
class MustHaveNonEmptyName extends \Exception
{
    public $message = 'Option must have a non-empty name';

    public $code = 400;

    /**
     * OptionMustHaveNonEmptyName constructor.
     */
    public function __construct()
    {
    }
}
