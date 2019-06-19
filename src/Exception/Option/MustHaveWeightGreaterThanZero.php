<?php declare(strict_types = 1);

namespace Gen3se\Engine\Exception\Option;

use Siwayll\Kapow\Level;

class MustHaveWeightGreaterThanZero extends \Siwayll\Kapow\Exception
{
    protected $optionName;

    public function __construct(string $optionName)
    {
        parent::__construct(
            'Option {optionName} must have a weight greater than zero',
            Level::ERROR
        );
        $this->optionName = $optionName;
    }
}
