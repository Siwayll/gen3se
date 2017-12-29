<?php

namespace Gen3se\Engine\Specs\Units\Mod\Append;

use Gen3se\Engine\Tests\Units\Provider\SimpleChoiceTrait;
use Gen3se\Engine\Tests\Units\Test;

class Append extends Test
{
    use SimpleChoiceTrait;

    public function shouldImplementModInterface()
    {
        $this
            ->skip('not implemented yet')
            ->given($this->newTestedInstance)
            ->class(get_class($this->testedInstance))
                ->hasInterface('Gen3se\Engine\Mod\ModInterface')
        ;
    }

    public function shouldGiveInstructions()
    {
        $this
            ->skip('not implemented yet')
            ->given($this->newTestedInstance())
            ->array($this->testedInstance->getInstructions())
                ->size->isEqualTo(1)

        ;
    }
}
