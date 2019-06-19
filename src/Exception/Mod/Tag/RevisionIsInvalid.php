<?php declare(strict_types = 1);

namespace Gen3se\Engine\Exception\Mod\Tag;

use Gen3se\Engine\Exception\ChoiceNameInterface;
use Gen3se\Engine\Exception\ChoiceNameTrait;
use Gen3se\Engine\Exception\OptionNameInterface;
use Gen3se\Engine\Exception\OptionNameTrait;
use Siwayll\Kapow\Exception;
use Siwayll\Kapow\Level;

class RevisionIsInvalid extends \Siwayll\Kapow\Exception implements ChoiceNameInterface, OptionNameInterface
{
    use ChoiceNameTrait, OptionNameTrait;

    protected $revisionValue;

    public function __construct($revisionValue)
    {
        parent::__construct(
            'The revision "{revisionValue}" is invalid in {optionName} in {choiceName}',
            Level::ERROR
        );
        $this->revisionValue = (string) $revisionValue;
    }
}
