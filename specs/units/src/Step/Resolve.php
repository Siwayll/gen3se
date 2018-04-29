<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Step;

use Gen3se\Engine\Specs\Units\Exception\ExceptionWithChoiceName;
use Gen3se\Engine\Specs\Units\Provider\Choice as MockChoiceProvider;
use Gen3se\Engine\Specs\Units\Test;
use Gen3se\Engine\Step;

class Resolve extends Test
{
    use MockChoiceProvider;
    use MockChoiceProvider\Collection;
    use MockChoiceProvider\Option;

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
                $step = $this->newTestedInstance()
            )
            ->if($step($choice))
            ->mock($choice)
                ->call('getOptionCollection')->once()
            ->mock($collection)
                ->call('getTotalWeight')->once()
                ->call('findByPositionInStack')->once()
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
                ($this->newTestedInstance())($choice);
            })
            ->mock($choice)
                ->call('getName')->once()
        ;
    }
}
