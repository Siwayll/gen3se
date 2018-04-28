<?php declare(strict_types = 1);

namespace Gen3se\Engine\Exception\Choice;

use Siwayll\Kapow\Exception;
use Siwayll\Kapow\Level;

/**
 * A Choice cannot change its name
 */
class CannotChangeItsName extends \Siwayll\Kapow\Exception
{
    /**
     * Choice name's
     */
    protected $optionName;

    /**
     * Message of the exception
     */
    public $message = 'Choice {optionName} cannot change its name';

    public $code = Level::ERROR;

    public function __construct(string $optionName)
    {
        $this->optionName = $optionName;
    }
}
