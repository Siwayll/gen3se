<?php

namespace Gen3se\Engine\Exception\Rand;

use Siwayll\Kapow\Exception;
use Siwayll\Kapow\Level;

/**
 * Class MinMustBeInferiorToMax
 * @package Gen3se\Engine\Exception\Rand
 */
class MinMustBeInferiorToMax extends Exception
{
    /**
     * @var string
     */
    protected $min;

    /**
     * @var string
     */
    protected $max;

    /**
     * @var string
     */
    public $message = 'Min ({min}) must be inferior to Max ({max})';

    /**
     * @var int
     */
    public $code = Level::ERROR;

    /**
     * MinMustBeInferiorToMax constructor.
     * @param int $min
     * @param int $max
     */
    public function __construct(int $min, int $max)
    {
        $this->min = (string) $min;
        $this->max = (string) $max;
    }
}
