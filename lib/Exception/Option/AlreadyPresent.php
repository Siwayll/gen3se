<?php

namespace Gen3se\Engine\Exception\Option;

use Gen3se\Engine\Exception\ChoiceNameInterface;
use Gen3se\Engine\Exception\ChoiceNameTrait;
use Siwayll\Kapow\Exception;
use Siwayll\Kapow\Level;

/**
 * Class OptionNotFound
 * @package Gen3se\Engine\Exception
 */
class AlreadyPresent extends Exception implements ChoiceNameInterface
{
    use ChoiceNameTrait;

    /**
     * @var string
     */
    protected $optionName;

    /**
     * @var string
     */
    public $message = 'Cannot add {optionName} in {choiceName}, it\'s already present';

    /**
     * @var int
     */
    public $code = Level::ERROR;

    /**
     * AlreadyPresent constructor.
     * @param string $optionName
     */
    public function __construct(string $optionName)
    {
        $this->optionName = $optionName;
    }
}
