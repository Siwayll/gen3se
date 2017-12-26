<?php

namespace Gen3se\Engine\Exception;

use Siwayll\Kapow\Exception;

/**
 * Class ChoiceMustHaveNonEmptyCollectionOfOptions
 * @package Gen3se\Engine\Exception
 */
class ChoiceMustHaveNonEmptyCollectionOfOptions extends Exception
{
    /**
     * @var string
     */
    protected $choiceName;

    /**
     * @var string
     */
    public $message = 'Choice {choiceName} must have a non-empty collection of Option';

    /**
     * @var int
     */
    public $code = 400;

    /**
     * ChoiceMustHaveNonEmptyCollectionOfOptions constructor.
     */
    public function __construct(string $choiceName)
    {
        $this->choiceName = $choiceName;
    }
}
