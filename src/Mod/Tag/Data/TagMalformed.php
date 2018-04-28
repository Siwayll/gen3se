<?php declare(strict_types = 1);

namespace Gen3se\Engine\Mod\Tag\Option\Data;

use Gen3se\Engine\Exception\ChoiceNameInterface;
use Gen3se\Engine\Exception\ChoiceNameTrait;
use Gen3se\Engine\Exception\OptionNameInterface;
use Gen3se\Engine\Exception\OptionNameTrait;
use Siwayll\Kapow\Level;

/**
 * Revision value is invalid
 */
class TagMalformed extends \Siwayll\Kapow\Exception implements ChoiceNameInterface, OptionNameInterface
{
    use ChoiceNameTrait, OptionNameTrait;

    protected $tag;

    /**
     * Message of the exception
     */
    public $message = 'The tag "{tag}" is invalid in {optionName} in {choiceName}';

    public $code = Level::ERROR;

    public function __construct($tag)
    {
        $this->tag = $tag;
    }
}
