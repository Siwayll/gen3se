<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Step\PostResolve;

use Gen3se\Engine\Specs\Units\Exception\ExceptionWithChoiceName;
use Gen3se\Engine\Specs\Units\Core\Provider\Choice as MockChoiceProvider;
use Gen3se\Engine\Specs\Units\Core\Provider\Result as MockResultProvider;
use Gen3se\Engine\Specs\Units\Core\Provider\Result\Filer as MockFilerProvider;
use Gen3se\Engine\Specs\Units\Core\Test;
use Gen3se\Engine\Step;

class Fil extends Test
{
    use MockChoiceProvider;
    use MockChoiceProvider\Collection;
    use MockChoiceProvider\Option;
    use MockFilerProvider;
    use MockResultProvider;

    public function shouldBeAStep()
    {
        $this
            ->testedClass
                ->hasInterface(Step::class)
        ;
    }

    public function shouldFilResultWithARandomSelectedOption()
    {
        $this
            ->given(
                $option = $this->createMockOption(),
                $collection = $this->createMockOptionCollection(
                    \rand(2, 15),
                    \rand(100, 3500),
                    $option
                ),
                $filer = $this->createMockFiler(),
                $choice = $this->createMockChoice(
                    null,
                    $collection,
                    $filer
                ),
                $result = $this->createMockResult(),
                $step = $this->newTestedInstance($result)
            )
            ->if($step($choice, $option))
            ->mock($result)
                ->call('registersTo')
                    ->withArguments($option, $filer)
                    ->once()
        ;
    }

    public function shouldCreateASimpleFilerIfNoneIsSpecified()
    {
        $this
            ->given(
                $option = $this->createMockOption(),
                $collection = $this->createMockOptionCollection(
                    \rand(2, 15),
                    \rand(100, 3500),
                    $option
                ),
                $choice = $this->createMockChoice(
                    null,
                    $collection
                ),
                $result = $this->createMockResult(),
                $step = $this->newTestedInstance($result)
            )
            ->if($step($choice, $option))
            ->mock($result)
                ->call('registersTo')
                    ->once()
        ;
    }
}
