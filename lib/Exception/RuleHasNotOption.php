<?php

namespace Gen3se\Engine\Exception;

/**
 * class Exception
 */
class RuleHasNotOption extends \Exception
{

    public $ruleName;

    public $optionName;

    public $message = '%s rule doesn\'t have %s option';

    /**
     * RuleHasNotOption constructor.
     * @param string $ruleName
     * @param string $optionName
     */
    public function __construct(
        string $ruleName,
        string $optionName
    ) {
        $this->ruleName = $ruleName;
        $this->optionName = $optionName;
    }
}

