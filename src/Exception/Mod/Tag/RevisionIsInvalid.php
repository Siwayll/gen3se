<?php declare(strict_types = 1);

namespace Gen3se\Engine\Exception\Mod\Tag;

use Gen3se\Engine\Exception\ChoiceNameInterface;
use Gen3se\Engine\Exception\ChoiceNameTrait;
use Gen3se\Engine\Exception\OptionNameInterface;
use Gen3se\Engine\Exception\OptionNameTrait;
use Siwayll\Kapow\Exception;
use Siwayll\Kapow\Level;

/**
 * Revision value is invalid
 */
class RevisionIsInvalid extends \Siwayll\Kapow\Exception implements ChoiceNameInterface, OptionNameInterface
{
    use ChoiceNameTrait, OptionNameTrait;

    protected $revisionValue;

    /**
     * Message of the exception
     */
    public $message = 'The revision "{revisionValue}" is invalid in {optionName} in {choiceName}';

    public $code = Level::ERROR;

    public function __construct($revisionValue)
    {
        $this->revisionValue = (string) $revisionValue;
    }
}
