<?php

namespace Gen3se\Engine\Specs\Units\Mod;

use Gen3se\Engine\Tests\Units\Test;

class Collection extends Test
{
    protected function createMockMod()
    {
        $mock = new \mock\Gen3se\Engine\Mod\ModInterface();
        return $mock;
    }

    protected function createMockModStepable(string $stepName)
    {
        $mock = new \mock\Gen3se\Engine\Mod\StepableInterface();
        $mock->getMockController()->isUpForStep = function ($name) use ($stepName) {
            if ($name === $stepName) {
                return true;
            }
            return false;
        };
        return $mock;
    }

    public function shouldBeCapableOfAddAMod()
    {
        $this
            ->given($this->newTestedInstance())
            ->object($this->testedInstance->add($this->createMockMod()))
                ->isTestedInstance()
        ;
    }

    public function shouldBeCountable()
    {
        $this
            ->given(
                $this->newTestedInstance()
            )
            ->testedClass
                ->hasInterface('\Countable')
            ->integer(count($this->testedInstance))
                ->isEqualTo(0)
            ->if($this->testedInstance->add($this->createMockMod()))
            ->integer(count($this->testedInstance))
                ->isEqualTo(1)
        ;
    }

    public function shouldBeFilterByStepName()
    {
        $this
            ->given(
                $this->newTestedInstance(),
                $modOne = $this->createMockMod(),
                $modTwo = $this->createMockModStepable('prepare'),
                $modThree = $this->createMockModStepable('prepare'),
                $this->testedInstance->add($modOne),
                $this->testedInstance->add($modTwo),
                $this->testedInstance->add($modThree)
            )
            ->generator($this->testedInstance->getModForStep('noMod'))
                ->hasSize(0)
            ->generator($this->testedInstance->getModForStep('prepare'))
                ->yields->object->isInstanceOf($modTwo)
                ->yields->object->isInstanceOf($modThree)
            ->generator($this->testedInstance->getModForStep('prepare'))
                ->hasSize(2)
        ;
    }
}
