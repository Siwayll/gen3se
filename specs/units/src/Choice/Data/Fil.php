<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Choice\Data;

use Gen3se\Engine\Choice\Data;
use Gen3se\Engine\Specs\Units\Core\Test;

class Fil extends Test
{
    public function shouldImplementChoiceDataInterface()
    {
        $this
            ->testedClass
                ->hasInterface(Data::class)
        ;
    }

    public function shouldReturnDepth()
    {
        $this
            ->given(
                $depth = [\uniqid(), \uniqid(), \uniqid()],
                $this->newTestedInstance(...$depth)
            )
            ->array($this->testedInstance->getDepth())
                ->isEqualTo($depth)
        ;
    }
}
