<?php

namespace Gen3se\Engine\Specs\Units\Choice\Option\Data;

use Gen3se\Engine\Choice\Option\DataInterface;
use Gen3se\Engine\Specs\Units\Test;

class Text extends Test
{
    public function shouldImplementOptionDataInterface()
    {
        $this
            ->testedClass
                ->hasInterface(DataInterface::class)
        ;
    }

    public function shouldBeInstantiatedWithOnlyAString()
    {
        $this
            ->object($this->newTestedInstance('toto'))
            ->exception(function () {
                $this->newTestedInstance(new \stdClass());
            })
        ;
    }

    public function shouldConvertItselfInArrayWithTextKey()
    {
        $this
            ->given(
                $this->newTestedInstance('Lorem ipsum')
            )
            ->array($this->testedInstance->toArray())
                ->hasKey('text')
        ;
    }
}
