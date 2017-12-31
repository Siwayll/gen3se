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
                $this->newTestedInstance()
            )
        ;
    }
}
