<?php declare(strict_types = 1);

namespace Gen3se\Engine\Mod\Tag\Data;

use Gen3se\Engine\Choice\Option\Data as OptionData;

interface AddTag extends OptionData
{
    /**
     * Get the list of tags to add
     */
    public function getTagsToAdd(): array;
}
