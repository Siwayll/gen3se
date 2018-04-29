<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Choice\Data;

use Gen3se\Engine\Choice\Data;
use Gen3se\Engine\Specs\Units\Test;

class Fil extends Test
{
    public function shouldImplementChoiceDataInterface()
    {
        $this
            ->testedClass
                ->hasInterface(Data::class)
        ;
    }
}
