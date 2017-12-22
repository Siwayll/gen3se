<?php

namespace Gen3se\Engine\Exception;

/**
 * class Exception
 */
class RuleDoesNotExist extends \Exception
{

    public $choiceName;

    public $ruleName;

    public $message = '%s choice doesn\'t have %s rules';

    /**
     * RuleDoesNotExist constructor.
     * @param string $choiceName
     * @param string $ruleName
     */
    public function __construct(
        string $choiceName,
        string $ruleName
    ) {
        $this->choiceName = $choiceName;
        $this->ruleName = $ruleName;
    }
}

