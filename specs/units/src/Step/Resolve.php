<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Step;

use Gen3se\Engine\Choice\Data\Fil;
use Gen3se\Engine\Specs\Units\Exception\ExceptionWithChoiceName;
use Gen3se\Engine\Specs\Units\Provider\Choice as MockChoiceProvider;
use Gen3se\Engine\Specs\Units\Provider\Result as MockResultProvider;
use Gen3se\Engine\Specs\Units\Provider\Result\Filer as MockFilerProvider;
use Gen3se\Engine\Specs\Units\Test;
use Gen3se\Engine\Step;

class Resolve extends Test
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

    public function shouldTakeAChoiceAndSelectARandomOption()
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
                $step = $this->newTestedInstance($this->createMockResult())
            )
            ->if($step($choice))
            ->mock($choice)
                ->call('getOptionCollection')->once()
            ->mock($collection)
                ->call('getTotalWeight')->once()
                ->call('findByPositionInStack')->once()
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
            ->if($step($choice))
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
            ->if($step($choice))
            ->mock($result)
                ->call('registersTo')
                    ->once()
        ;
    }

    public function shouldAddChoiceNameToExceptionIfNeeded()
    {
        $this
            ->given(
                $collection = $this->newMockInstance(\Gen3se\Engine\Choice\Option\CollectionInterface::class),
                $collection->getMockController()->getTotalWeight = 100,
                $collection->getMockController()->findByPositionInStack = function () {
                    throw new ExceptionWithChoiceName();
                },
                $choice = $this->createMockChoice(
                    null,
                    $collection
                )
            )
            ->KapowException(function () use ($choice) {
                ($this->newTestedInstance($this->createMockResult()))($choice);
            })
            ->mock($choice)
                ->call('getName')->once()
        ;
    }
}
