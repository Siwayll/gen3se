<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Choice\Option\Data;

use Gen3se\Engine\Choice\Option\Data;
use Gen3se\Engine\Specs\Units\Test;

class Simple extends Test
{
    public function shouldImplementOptionDataInterface()
    {
        $this
            ->testedClass
            ->hasInterface(Data::class)
        ;
    }

    public function shouldConvertItselfInArrayWithTextKey()
    {
        $this
            ->given(
                $this->newTestedInstance('key', 'Lorem ipsum')
            )
            ->array($this->testedInstance->toArray())
                ->hasKey('key')
                ->string['key']->isEqualTo('Lorem ipsum')
        ;
    }
}
