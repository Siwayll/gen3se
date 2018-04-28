<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Choice;

use Gen3se\Engine\Specs\Units\Exception\ExceptionWithChoiceName;
use Gen3se\Engine\Specs\Units\Provider\Choice as MockChoiceProvider;
use Gen3se\Engine\Specs\Units\Test;

class Resolver extends Test
{
    use MockChoiceProvider;
    use MockChoiceProvider\Collection;
    use MockChoiceProvider\Option;

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
                )
            )
            ->object($this->newTestedInstance($choice))
            ->mock($choice)
                ->call('getOptionCollection')->once()
            ->mock($collection)
                ->call('getTotalWeight')->once()
                ->call('findByPositionInStack')->once()
            ->object($this->testedInstance->getPickedOption())
                ->isEqualTo($option)
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
                $this->newTestedInstance($choice);
            })
            ->mock($choice)
                ->call('getName')->once()
        ;
    }
}
