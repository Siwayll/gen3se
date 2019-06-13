<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Mod;

use Gen3se\Engine\Specs\Units\Core\Provider\ModCollectionTrait;
use Gen3se\Engine\Specs\Units\Core\Test;

class Collection extends Test
{
    use ModCollectionTrait;

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
            ->integer(\count($this->testedInstance))
                ->isEqualTo(0)
            ->if($this->testedInstance->add($this->createMockMod()))
            ->integer(\count($this->testedInstance))
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
