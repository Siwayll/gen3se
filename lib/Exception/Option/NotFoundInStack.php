<?php

namespace Gen3se\Engine\Exception\Option;

use Gen3se\Engine\Exception\ChoiceNameTrait;
use Siwayll\Kapow\Exception;
use Siwayll\Kapow\Level;

/**
 * Class OptionNotFoundInStack
 * @package Gen3se\Engine\Exception
 */
class NotFoundInStack extends Exception
{
    use ChoiceNameTrait;

    /**
     * @var string
     */
    protected $stackPosition;

    /**
     * @var string
     */
    public $message = 'Cannot find options in collection at stack position "{stackPosition}" for {choiceName}';

    /**
     * @var int
     */
    public $code = Level::ERROR;

    /**
     * OptionNotFoundInStack constructor.
     * @param int $stackPosition
     */
    public function __construct(int $stackPosition)
    {
        $this->stackPosition = (string) $stackPosition;
    }
}
