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
class TagMalformed extends \Siwayll\Kapow\Exception implements ChoiceNameInterface, OptionNameInterface
{
    use ChoiceNameTrait, OptionNameTrait;

    protected $tag;

    public function __construct($tag)
    {
        parent::__construct(
            'The tag "{tag}" is invalid in {optionName} in {choiceName}',
            Level::ERROR
        );
        $this->tag = $tag;
    }
}
