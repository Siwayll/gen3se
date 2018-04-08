<?php declare(strict_types = 1);

namespace Gen3se\Engine\Mod\Tag\Option\Data\Simple;

use Gen3se\Engine\Mod\Tag\Option\Data\Tag as OptionDataTag;
use Gen3se\Engine\Mod\Tag\Option\Data\TagMalformed;

class Tag implements OptionDataTag
{
    const TAGNAME_VALIDATOR = '@^[A-Z0-9-_]+$@';

    protected $tagName;
    protected $revisionValue;

    public function __construct(string $tagName, $revisionValue)
    {
        $this->tagName = $this->validateTagname($tagName);
        $this->revisionValue = $revisionValue;
    }

    public function toArray(): array
    {
        return [];
    }

    public function getTagName(): string
    {
        return $this->tagName;
    }

    public function getRevisionValue()
    {
        return $this->revisionValue;
    }

    /**
     * Check if the tag name does not contain illegal char
     * #exception
     * if the string tag name not validate preg match with TAGNAME_VALIDATOR
     */
    private function validateTagname(string $tagName): string
    {
        if (preg_match(self::TAGNAME_VALIDATOR, $tagName) === 1) {
            return $tagName;
        }

        throw new TagMalformed($tagName);
    }
}
