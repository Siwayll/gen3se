<?php declare(strict_types = 1);

namespace Gen3se\Engine\Exception\Mod\Tag;

use Gen3se\Engine\Exception\ChoiceNameInterface;
use Gen3se\Engine\Exception\ChoiceNameTrait;
use Gen3se\Engine\Exception\OptionNameInterface;
use Gen3se\Engine\Exception\OptionNameTrait;
use Siwayll\Kapow\Level;

class RevisionTypeInvalid extends \Siwayll\Kapow\Exception implements ChoiceNameInterface, OptionNameInterface
{
    use ChoiceNameTrait, OptionNameTrait;

    protected $varType;

    public function __construct($var)
    {
        parent::__construct(
            'Revision value must be of the type string or numeric ({varType} given) in {optionName} in {choiceName}',
            Level::ERROR
        );
        $this->varType = \gettype($var);
    }
}
