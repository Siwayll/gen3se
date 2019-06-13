<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Choice\Option\Data;

use Gen3se\Engine\Choice\Option\Data;
use Gen3se\Engine\Specs\Units\Core\Test;

class Text extends Test
{
    public function shouldImplementOptionDataInterface()
    {
        $this
            ->testedClass
                ->hasInterface(Data::class)
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
