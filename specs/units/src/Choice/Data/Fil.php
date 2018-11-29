<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Choice\Data;

use Gen3se\Engine\Choice\Data;
use Gen3se\Engine\Specs\Units\Core\Test;

class Fil extends Test
{
    public function shouldImplementChoiceDataInterface()
    {
        $this->skip('Not implemented yet');
        $this
            ->testedClass
                ->hasInterface(Data::class)
        ;
    }

    public function shouldReturnDepth()
    {
        $this->skip('Not implemented yet');
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
