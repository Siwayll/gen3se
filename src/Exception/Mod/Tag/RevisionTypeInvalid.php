<?php

namespace Gen3se\Engine\Exception\Mod\Tag;

use Gen3se\Engine\Exception\ChoiceNameInterface;
use Gen3se\Engine\Exception\ChoiceNameTrait;
use Gen3se\Engine\Exception\OptionNameInterface;
use Gen3se\Engine\Exception\OptionNameTrait;
use Siwayll\Kapow\Exception;
use Siwayll\Kapow\Level;

/**
 * Type of the revision value is invalid
 */
class RevisionTypeInvalid extends Exception implements ChoiceNameInterface, OptionNameInterface
{
    use ChoiceNameTrait, OptionNameTrait;

    protected $varType;

    /**
     * Message of the exception
     */
    public $message = 'Revision value must be of the type string or numeric '
        . '({varType} given) in {optionName} in {choiceName}';

    public $code = Level::ERROR;

    public function __construct($var)
    {
        $this->varType = gettype($var);
    }
}
