<?php

namespace Gen3se\Engine\Specs\Units\Choice;

use Gen3se\Engine\Choice\Choice;
use Gen3se\Engine\Option\Collection;
use Gen3se\Engine\Option\Option;
use Gen3se\Engine\Tests\Units\Provider\SimpleChoiceTrait;
use Gen3se\Engine\Tests\Units\Test;
use Siwayll\Kapow\Level;

class Preparer extends Test
{
    use SimpleChoiceTrait;

    protected function choiceProvider()
    {
        return [
            $this->getEyeColorChoice(),
            $this->getHairColorChoice()
        ];
    }

    /**
     * @dataProvider choiceProvider
     */
    public function shouldCloneAChoice(Choice $choice)
    {
        $this
            ->given(
                $option = $choice->getOptionCollection()->findByPositonInStack(1)
            )
            ->object($this->newTestedInstance($choice))
            ->object($this->testedInstance->getLoadedChoice())
                ->isInstanceOf('Gen3se\Engine\Choice\Choice')
                ->isCloneOf($choice)
            ->string($this->testedInstance->getLoadedChoice()->getName())
                ->isEqualTo($choice->getName())
            ->object($this->testedInstance->getLoadedChoice()->getOptionCollection())
                ->isCloneOf($choice->getOptionCollection())
            ->object($this->testedInstance->getLoadedChoice()->getOptionCollection()->get($option->getName()))
                ->isCloneOf($option)
        ;
    }
}
