<?php declare(strict_types = 1);

namespace Gen3se\Engine\Exception\Option;

use Siwayll\Kapow\Exception;
use Siwayll\Kapow\Level;

class CantUnsetMandatoryData extends \Siwayll\Kapow\Exception
{
    /** @var string */
    protected $optionName;

    public function __construct(string $optionName)
    {
        parent::__construct(
            'Option {optionName} cant unset mandatory data',
            Level::ERROR
        );
        $this->optionName = $optionName;
    }
}
