<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Choice\Option;

use Gen3se\Engine\Specs\Units\Provider\Choice\Option as MockOptionProvider;
use Gen3se\Engine\Specs\Units\Test;
use Siwayll\Kapow\Level;

class Collection extends Test
{
    use MockOptionProvider;

    public function shouldBeCapableOfAddAnOption()
    {
        $this
            ->given($this->newTestedInstance())
            ->object($this->testedInstance->add($this->createMockOption()))
                ->isTestedInstance()
        ;
    }

    public function shouldReturnOptionByName()
    {
        $this
            ->given(
                $optionName = 'opt-1',
                $this->newTestedInstance()
            )
            ->if($this->testedInstance->add($this->createMockOption($optionName)))
            ->object($this->testedInstance->get($optionName))
            ->KapowException(
                function () {
                    $this->testedInstance->get('non-opt');
                }
            )
                ->hasMessage('Option {optionName} not found')
                ->hasKapowMessage('Option non-opt not found')
                ->hasCode(Level::ERROR)
        ;
    }

    public function shouldBeCountable()
    {
        $this
            ->given(
                $this->newTestedInstance()
            )
            ->integer(count($this->testedInstance))
                ->isEqualTo(0)
            ->if($this->testedInstance->add($this->createMockOption()))
            ->integer(count($this->testedInstance))
                ->isEqualTo(1)
        ;
    }

    public function shouldNotAcceptMultipleChoicesWithTheSameName()
    {
        $this
            ->given($this->newTestedInstance())
            ->object($this->testedInstance->add($this->createMockOption('opt1')))
                ->isTestedInstance()
            ->KapowException(
                function () {
                    $this->testedInstance->add($this->createMockOption('opt1'));
                }
            )
                ->hasKapowMessage('Cannot add opt1 in {choiceName}, it\'s already present')
                ->hasCode(Level::ERROR)
        ;
    }

    public function shouldCalculateTheTotalWeightOptionS()
    {
        $this
            ->given(
                $this->newTestedInstance(),
                $this->testedInstance->add($this->createMockOption(null, 500)),
                $this->testedInstance->add($this->createMockOption(null, 500))
            )
            ->integer(count($this->testedInstance))
                ->isEqualTo(2)
            ->integer($this->testedInstance->getTotalWeight())
                ->isEqualTo(1000)
        ;
    }

    public function shouldFindAnOptionByPositionInTheStack()
    {
        $this
            ->given(
                $optionOne = $this->createMockOption(null, 500),
                $optionTwo = $this->createMockOption(null, 500),
                $optionThree = $this->createMockOption(null, 0),
                $optionFour = $this->createMockOption(null, 10),
                $this->newTestedInstance()
            )
            ->if($this->testedInstance->add($optionOne))
            ->and($this->testedInstance->add($optionTwo))
            ->and($this->testedInstance->add($optionThree))
            ->and($this->testedInstance->add($optionFour))
            ->object($this->testedInstance->findByPositionInStack(485))
                ->isIdenticalTo($optionOne)
            ->object($this->testedInstance->findByPositionInStack(893))
                ->isIdenticalTo($optionTwo)
            ->object($this->testedInstance->findByPositionInStack(1000))
                ->isIdenticalTo($optionTwo)
            ->object($this->testedInstance->findByPositionInStack(1001))
                ->isIdenticalTo($optionFour)
            ->object($this->testedInstance->findByPositionInStack(1010))
                ->isIdenticalTo($optionFour)

            ->given(
                $optionOne = $this->createMockOption(null, 0),
                $optionTwo = $this->createMockOption(null, 0),
                $optionThree = $this->createMockOption(null, 0),
                $this->newTestedInstance()
            )
            ->if($this->testedInstance->add($optionOne))
            ->and($this->testedInstance->add($optionTwo))
            ->and($this->testedInstance->add($optionThree))
            ->KapowException(
                function () {
                    $this->testedInstance->findByPositionInStack(0);
                }
            )
                ->hasKapowMessage(
                    'Cannot find options in collection at stack position "0" for {choiceName}'
                )
                ->hasCode(Level::ERROR)
        ;
    }

    public function shouldNotAcceptIrelevantPosition()
    {
        $this
            ->given(
                $optionOne = $this->createMockOption(null, 500),
                $this->newTestedInstance()
            )
            ->if($this->testedInstance->add($optionOne))
            ->exception(function () {
                $this->testedInstance->findByPositionInStack('foo');
            })
                ->isInstanceOf('\TypeError')
            ->KapowException(
                function () {
                    $this->testedInstance->findByPositionInStack(-1);
                }
            )
                ->hasKapowMessage('Position "-1" must be relevant in [0,500] for {choiceName}')
                ->hasCode(Level::CRITICAL)
            ->KapowException(
                function () {
                    $this->testedInstance->findByPositionInStack(550);
                }
            )
                ->hasKapowMessage('Position "550" must be relevant in [0,500] for {choiceName}')
                ->hasCode(Level::CRITICAL)
        ;
    }

    public function shouldCloneAllTheOptionsWhenItsCloned()
    {
        $this
            ->given(
                $optionOne = $this->createMockOption('opt1', 500),
                $optionTwo = $this->createMockOption('opt2', 500),
                $this->newTestedInstance(),
                $this->testedInstance->add($optionOne),
                $this->testedInstance->add($optionTwo),
                $clone = clone $this->testedInstance
            )
            ->object($clone)
                ->isCloneOf($this->testedInstance)
            ->object($clone->get('opt1'))
                ->isCloneOf($optionOne)
            ->object($clone->get('opt2'))
                ->isCloneOf($optionTwo)
        ;
    }

    public function shouldHaveAnIterateMethod()
    {
        $this
            ->given(
                $optionOne = $this->createMockOption(null, 500),
                $optionTwo = $this->createMockOption(null, 500),
                $this->newTestedInstance(),
                $this->testedInstance->add($optionOne),
                $this->testedInstance->add($optionTwo)
            )
            ->generator($this->testedInstance->each())
                ->hasSize(2)
            ->generator($this->testedInstance->each())
                ->yields->object->isInstanceOf($optionOne)
                ->yields->object->isInstanceOf($optionTwo)
        ;
    }

    public function shouldBeInstanciedWithOptions()
    {
        $this
            ->given(
                $optionOne = $this->createMockOption(),
                $optionTwo = $this->createMockOption()
            )
            ->if($this->newTestedInstance($optionOne))
            ->integer(count($this->testedInstance))
                ->isEqualTo(1)
            ->if($this->newTestedInstance($optionOne, $optionTwo))
            ->integer(count($this->testedInstance))
                ->isEqualTo(2)
        ;
    }
}
