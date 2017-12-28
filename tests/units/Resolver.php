<?php

namespace Gen3se\Engine\Specs\Units;

use Gen3se\Engine\Choice\Choice;
use Gen3se\Engine\Tests\Units\Provider\SimpleChoiceTrait;
use Gen3se\Engine\Tests\Units\Test;

class Resolver extends Test
{
    use SimpleChoiceTrait;

    protected function choiceProvider()
    {
        return [
            $this->getEyeColorChoice()
        ];
    }

    /**
     * @param Choice $choice
     * @dataProvider choiceProvider
     * @throws \Gen3se\Engine\Exception\Option\NotFound
     */
    public function shouldTakeAChoiceAndSelectARandomOption(Choice $choice)
    {
        $this
            ->object($this->newTestedInstance($choice))
            ->object($this->testedInstance->getPickedOption())
                ->isInstanceOf('Gen3se\Engine\Option\Option')
            ->variable($choice->getOptionCollection()->get($this->testedInstance->getPickedOption()->getName()))
        ;
    }
}
