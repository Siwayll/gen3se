<?php

namespace Gen3se\Engine\Exception;

use Siwayll\Kapow\Exception;
use Siwayll\Kapow\Level;

/**
 * Class OptionMustHaveWeightGreaterThanZero
 * @package Gen3se\Engine\Exception
 */
class OptionMustHaveWeightGreaterThanZero extends Exception
{
    /**
     * @var string
     */
    protected $optionName;

    /**
     * @var string
     */
    public $message = 'Option {optionName} must have a weight greater than zero';

    /**
     * @var int
     */
    public $code = Level::ERROR;

    /**
     * OptionMustHaveWeightGreaterThanZero constructor.
     * @param string $optionName
     */
    public function __construct(string $optionName)
    {
        $this->optionName = $optionName;
    }
}
