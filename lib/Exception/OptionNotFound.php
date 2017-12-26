<?php

namespace Gen3se\Engine\Exception;

/**
 * Class OptionNotFound
 * @package Gen3se\Engine\Exception
 */
class OptionNotFound extends \Exception
{
    protected $optionName;

    public $message = 'Option {optionName} not found';

    public $code = 400;

    /**
     * OptionMustHaveNonEmptyName constructor.
     */
    public function __construct(string $optionName)
    {
        $this->optionName = $optionName;
    }
}
