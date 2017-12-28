<?php

namespace Gen3se\Engine\Exception;

use Siwayll\Kapow\Exception;
use Siwayll\Kapow\Level;

/**
 * Class OptionsCannotChangeItsName
 * @package Gen3se\Engine\Exception
 */
class OptionsCannotChangeItsName extends Exception
{
    /**
     * @var string
     */
    protected $optionName;

    /**
     * @var string
     */
    public $message = 'Option {optionName} cannot change its name';

    /**
     * @var int
     */
    public $code = Level::ERROR;

    /**
     * OptionsCannotChangeItsName constructor.
     * @param string $optionName
     */
    public function __construct(string $optionName)
    {
        $this->optionName = $optionName;
    }
}
