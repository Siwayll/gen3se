<?php

namespace Gen3se\Engine\Mod\Tag;

class TagData implements DataInterface
{
    protected $tagName;
    protected $revisionValue;

    public function __construct(string $tagName, $revisionValue)
    {
        $this->tagName = $tagName;
        $this->revisionValue = $revisionValue;
    }

    public function toArray(): array
    {
        return [Tag::TAG_FIELDNAME => [$this->tagName => $this->revisionValue]];
    }

    public function getTagName(): string
    {
        return $this->tagName;
    }

    public function getRevisionValue()
    {
        return $this->revisionValue;
    }
}
