<?php declare(strict_types = 1);

namespace Gen3se\Engine\Exception\Option;

use Gen3se\Engine\Exception\ChoiceNameInterface;
use Gen3se\Engine\Exception\ChoiceNameTrait;
use Siwayll\Kapow\Exception;
use Siwayll\Kapow\Level;

class AlreadyPresent extends \Siwayll\Kapow\Exception implements ChoiceNameInterface
{
    use ChoiceNameTrait;

    /** @var string */
    protected $optionName;

    public function __construct(string $optionName)
    {
        parent::__construct(
            'Cannot add {optionName} in {choiceName}, it\'s already present',
            Level::ERROR
        );
        $this->optionName = $optionName;
    }
}
