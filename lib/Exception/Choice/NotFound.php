<?php

namespace Gen3se\Engine\Exception\Choice;

use Siwayll\Kapow\Exception;
use Siwayll\Kapow\Level;

/**
 * Class NotFound
 * @package Gen3se\Engine\Exception\Choice
 */
class NotFound extends Exception
{
    /**
     * @var string
     */
    protected $choiceName;

    /**
     * @var string
     */
    public $message = 'Choice {choiceName} not found';

    /**
     * @var int
     */
    public $code = Level::ERROR;

    /**
     * NotFound constructor.
     * @param string $choiceName
     */
    public function __construct(string $choiceName)
    {
        $this->choiceName = $choiceName;
    }
}
