<?php declare(strict_types = 1);

namespace Gen3se\Engine\Exception\Option;

use Siwayll\Kapow\Exception;

/**
 * Class OptionCantUnsetMandatoryData
 * @package Gen3se\Engine\Exception
 */
class CantUnsetMandatoryData extends Exception
{
    /**
     * @var string
     */
    protected $optionName;

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
