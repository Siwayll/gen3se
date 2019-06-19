<?php declare(strict_types = 1);

namespace Gen3se\Engine\Exception\Option;

use Siwayll\Kapow\Level;

class NotFound extends \Siwayll\Kapow\Exception
{
    protected $optionName;

    public function __construct(string $optionName)
    {
        parent::__construct(
            'Option {optionName} not found',
            Level::ERROR
        );
        $this->optionName = $optionName;
    }
}
