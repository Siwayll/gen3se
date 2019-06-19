<?php declare(strict_types = 1);

namespace Gen3se\Engine\Exception\Rand;

use Siwayll\Kapow\Level;

class MinMustBeInferiorToMax extends \Siwayll\Kapow\Exception
{
    protected $min;

    protected $max;

    public function __construct(int $min, int $max)
    {
        parent::__construct(
            'Min ({min}) must be inferior to Max ({max})',
            Level::ERROR
        );
        $this->min = (string) $min;
        $this->max = (string) $max;
    }
}
