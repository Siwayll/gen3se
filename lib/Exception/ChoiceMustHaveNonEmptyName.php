<?php

namespace Gen3se\Engine\Exception;

/**
 * Class ChoiceMustHaveNonEmptyName
 * @package Gen3se\Engine\Exception
 */
class ChoiceMustHaveNonEmptyName extends \Exception
{
    public $message = 'Choice must have a non-empty name';

    public $code = 400;

    /**
     * ChoiceMustHaveNonEmptyName constructor.
     */
    public function __construct()
    {
    }
}
