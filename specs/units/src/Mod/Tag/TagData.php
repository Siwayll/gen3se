<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Mod\Tag;

use Gen3se\Engine\Choice\Option\DataInterface;
use Gen3se\Engine\Specs\Units\Test;

class TagData extends Test
{
    public function shouldImplementOptionDataInterface()
    {
        $this
            ->testedClass
                ->hasInterface(DataInterface::class)
                ->hasInterface(\Gen3se\Engine\Mod\Tag\DataInterface::class)
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
