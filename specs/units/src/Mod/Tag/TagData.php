<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Mod\Tag;

use Gen3se\Engine\Choice\Option\Data;
use Gen3se\Engine\Specs\Units\Core\Test;

/**
 * @ignore
 */
class TagData extends Test
{
    public function shouldImplementOptionDataInterface()
    {
        $this
            ->testedClass
                ->hasInterface(Data::class)
                ->hasInterface(\Gen3se\Engine\Mod\Tag\Option\Data\Tag::class)
        ;
    }

    public function shouldReturnEmptyOnConvertToArray()
    {
        $this
            ->given(
                $this->newTestedInstance('TAGNAME', 200)
            )
            ->array($this->testedInstance->toArray())
                ->isEmpty()
        ;
    }
}
