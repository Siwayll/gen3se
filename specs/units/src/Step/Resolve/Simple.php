<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Step\Resolve;

use Gen3se\Engine\Result;
use Gen3se\Engine\Specs\Units\Core\Exception\ExceptionWithChoiceName;
use Gen3se\Engine\Specs\Units\Core\Provider\Choice as MockChoiceProvider;
use Gen3se\Engine\Specs\Units\Core\Provider\Result\Filer as MockFilerProvider;
use Gen3se\Engine\Specs\Units\Core\Test;
use Gen3se\Engine\Step;

class Simple extends Test
{
    use MockChoiceProvider;
    use MockChoiceProvider\Collection;
    use MockFilerProvider;

    public function shouldBeAStep()
    {
        $this
            ->testedClass
                ->hasInterface(Step::class)
        ;
    }
}
