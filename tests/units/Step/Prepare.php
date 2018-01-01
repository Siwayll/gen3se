<?php

namespace Gen3se\Engine\Specs\Units\Step;

use Gen3se\Engine\Choice\Choice;
use Gen3se\Engine\Mod\Collection as ModCollection;
use Gen3se\Engine\Tests\Units\Provider\ModCollectionTrait;
use Gen3se\Engine\Tests\Units\Provider\SimpleChoiceTrait;
use Gen3se\Engine\Tests\Units\Test;

class Prepare extends Test
{
    use SimpleChoiceTrait, ModCollectionTrait;

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
                $modCollection = new ModCollection(),
                $option = $choice->getOptionCollection()->findByPositonInStack(1)
            )
            ->object($this->newTestedInstance($choice, $modCollection))
            ->object(call_user_func($this->testedInstance))
                ->isInstanceOf('Gen3se\Engine\Choice\Choice')
                ->isCloneOf($choice)
            ->string(call_user_func($this->testedInstance)->getName())
                ->isEqualTo($choice->getName())
            ->object(call_user_func($this->testedInstance)->getOptionCollection())
                ->isCloneOf($choice->getOptionCollection())
            ->object(call_user_func($this->testedInstance)->getOptionCollection()->get($option->getName()))
                ->isCloneOf($option)
        ;
    }

    public function shouldExecuteMod()
    {
        $this
            ->given(
                $choice = $this->getEyeColorChoice(),
//                $choice->getOptionCollection()->findByPositonInStack(1)
                $modCollection = new ModCollection(),
                $modMock = $this->createMockModStepable('>prepare'),
                $modCollection->add($modMock)
            )
            ->object($this->newTestedInstance($choice, $modCollection))
            ->mock($modMock)
                ->call('isUpForStep')->once()
        ;
    }
}
