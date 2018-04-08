<?php

namespace Gen3se\Engine\Mod\Tag;

class TagData implements DataInterface
{
    const TAGNAME_VALIDATOR = '@^[A-Za-z0-9-_]+$@';

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
