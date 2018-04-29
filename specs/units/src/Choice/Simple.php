<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Choice;

use Gen3se\Engine\Specs\Units\Provider\Choice\Collection as MockOptionCollectionProvider;
use Gen3se\Engine\Specs\Units\Provider\Step as MockStepProvider;
use Gen3se\Engine\Specs\Units\Test;
use Siwayll\Kapow\Level;

class Simple extends Test
{
    use MockOptionCollectionProvider;
    use MockStepProvider;

    protected function collectionProvider()
    {
        return [
            $this->createMockOptionCollection(1),
        ];
    }

    public function shouldHaveANonEmptyCollectionOfOptions()
    {
        $this
            ->given($collection = $this->createMockOptionCollection(1))
            ->KapowException(function () {
                $this->newTestedInstance('EmptyCollection', $this->createMockOptionCollection(0));
            })
                ->hasMessage('Choice {choiceName} must have a non-empty collection of Option')
                ->hasKapowMessage('Choice EmptyCollection must have a non-empty collection of Option')
                ->hasCode(Level::ERROR)
            ->given(
                $name = 'choice',
                $this->newTestedInstance($name, $collection)
            )
            ->object($this->testedInstance->getOptionCollection())
                ->isIdenticalTo($collection)
        ;
    }

    public function shouldHaveAName()
    {
        $this
            ->given(
                $collection = $this->createMockOptionCollection(1)
            )
            ->exception(function () use ($collection) {
                $this->newTestedInstance('', $collection);
            })
                ->hasMessage('Choice must have a non-empty name')
                ->hasCode(Level::ERROR)
            ->given(
                $name = 'choice',
                $this->newTestedInstance($name, $collection)
            )
            ->string($this->testedInstance->getName())
                ->isEqualTo($name)
        ;
    }

    public function shouldBeClonable()
    {
        $this
            ->given(
                $collection = $this->createMockOptionCollection(1),
                $this->newTestedInstance('choice', $collection)
            )
            ->if($clone = clone $this->testedInstance)
            ->object($clone)
                ->isCloneOf($this->testedInstance)
            ->object($clone->getOptionCollection())
                ->isCloneOf($collection)
        ;
    }

    public function shouldTreatsAllStepGiven()
    {
        $this
            ->given(
                $collection = $this->createMockOptionCollection(1),
                $this->newTestedInstance('choice', $collection),
                $stepOne = $this->createMockStep(
                    $stepOneCallable = function () use (&$stepOneArguments) {
                        $stepOneArguments = \func_get_args();
                    }
                ),
                $stepTwo = $this->createMockStep(
                    $stepTwoCallable = function () {
                    }
                )
            )
            ->if($this->testedinstance->treatsThis($stepOne, $stepTwo))
            ->mock($stepOne)
                ->call('__invoke')
                    ->once()
            ->mock($stepTwo)
                ->call('__invoke')
                    ->once()
            ->array($stepOneArguments)
                ->object[0]->isCloneOf($this->testedInstance)

        ;
    }
}
