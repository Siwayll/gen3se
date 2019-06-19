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

    public function __construct(string $optionName)
    {
        parent::__construct(
            'Choice {optionName} cannot change its name',
            Level::ERROR
        );
        $this->optionName = $optionName;
    }
}
