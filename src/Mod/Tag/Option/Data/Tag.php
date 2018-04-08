<?php declare(strict_types = 1);

namespace Gen3se\Engine\Mod\Tag\Option\Data;

use Gen3se\Engine\Choice\Option\Data as OptionData;

interface Tag extends OptionData
{
    /**
     * Get
     */
    public function getTagName(): string;

    /**
     *
     */
    public function getRevisionValue();
}
