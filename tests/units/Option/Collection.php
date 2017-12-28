<?php

namespace Gen3se\Engine\Specs\Units\Option;

use Gen3se\Engine\Tests\Units\Test;
use Gen3se\Engine\Option\Option;

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
                ->hasCode(400)
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
}
