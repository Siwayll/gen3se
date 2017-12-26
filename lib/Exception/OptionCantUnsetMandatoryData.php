<?php

namespace Gen3se\Engine\Exception;

/**
 * Class OptionCantUnsetMandatoryData
 * @package Gen3se\Engine\Exception
 */
class OptionCantUnsetMandatoryData extends \Exception
{
    /**
     * @var string
     */
    private $optionName;

    /**
     * @var string
     */
    protected $message = 'Option {optionName} cant unset mandatory data';

    /**
     * @var int
     */
    protected $code = 400;

    /**
     * OptionCantUnsetMandatoryData constructor.
     * @param string $optionName
     */
    public function __construct(string $optionName)
    {
        $this->optionName = $optionName;
    }
}