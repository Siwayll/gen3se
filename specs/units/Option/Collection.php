<?php

namespace Gen3se\Engine\Specs\Units\Option;

use Gen3se\Engine\Specs\Units\Test;
use Gen3se\Engine\Option\Option;
use Siwayll\Kapow\Level;

class Collection extends Test
{
    /**
     * @param string|null $name
     * @return \mock\Gen3se\Engine\Option\Option
     */
    public function createMockOption(string $name = null)
    {
        if ($name === null) {
            $name = uniqid();
        }
        $mock = new \mock\Gen3se\Engine\Option\Option();
        $mock->getMockController()->getName = function () use ($name) {
            return $name;
        };

        return $mock;
    }

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
                $optionName = 'opt-1',
                $this->newTestedInstance()
            )
            ->integer(count($this->testedInstance))
                ->isEqualTo(0)
            ->if($this->testedInstance->add($this->createMockOption($optionName)))
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
                $this->newTestedInstance()
            )
            ->if($this->testedInstance->add(new Option('opt1', 500)))
            ->and($this->testedInstance->add(new Option('opt2', 500)))
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
                $optionOne = new Option('opt1', 500),
                $optionTwo = new Option('opt2', 500),
                $optionThree = new Option('opt3', 0),
                $optionFour = new Option('opt4', 10),
                $this->newTestedInstance()
            )
            ->if($this->testedInstance->add($optionOne))
            ->and($this->testedInstance->add($optionTwo))
            ->and($this->testedInstance->add($optionThree))
            ->and($this->testedInstance->add($optionFour))
            ->object($this->testedInstance->findByPositonInStack(485))
                ->isIdenticalTo($optionOne)
            ->object($this->testedInstance->findByPositonInStack(893))
                ->isIdenticalTo($optionTwo)
            ->object($this->testedInstance->findByPositonInStack(1000))
                ->isIdenticalTo($optionTwo)
            ->object($this->testedInstance->findByPositonInStack(1001))
                ->isIdenticalTo($optionFour)
            ->object($this->testedInstance->findByPositonInStack(1010))
                ->isIdenticalTo($optionFour)

            ->given(
                $optionOne = new Option('opt1', 0),
                $optionTwo = new Option('opt2', 0),
                $optionThree = new Option('opt3', 0),
                $this->newTestedInstance()
            )
            ->if($this->testedInstance->add($optionOne))
            ->and($this->testedInstance->add($optionTwo))
            ->and($this->testedInstance->add($optionThree))
            ->KapowException(
                function () {
                    $this->testedInstance->findByPositonInStack(0);
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
                $optionOne = new Option('opt1', 500),
                $this->newTestedInstance()
            )
            ->if($this->testedInstance->add($optionOne))
            ->exception(function () {
                $this->testedInstance->findByPositonInStack('foo');
            })
                ->isInstanceOf('\TypeError')
            ->KapowException(
                function () {
                    $this->testedInstance->findByPositonInStack(-1);
                }
            )
                ->hasKapowMessage('Position "-1" must be relevant in [0,500] for {choiceName}')
                ->hasCode(Level::CRITICAL)
            ->KapowException(
                function () {
                    $this->testedInstance->findByPositonInStack(550);
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
                $optionOne = new Option('opt1', 500),
                $optionTwo = new Option('opt2', 500),
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
                $optionOne = new Option('opt1', 500),
                $optionTwo = new Option('opt2', 500),
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
}
