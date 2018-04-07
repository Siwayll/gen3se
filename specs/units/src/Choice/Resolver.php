<?php

namespace Gen3se\Engine\Specs\Units\Choice;

use Gen3se\Engine\Choice\Choice;
use Gen3se\Engine\Choice\Option\Collection;
use Gen3se\Engine\Choice\Option;
use Gen3se\Engine\Specs\Units\Provider\SimpleChoiceTrait;
use Gen3se\Engine\Specs\Units\Test;
use Siwayll\Kapow\Level;

class Resolver extends Test
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
     * @param Choice $choice
     * @dataProvider choiceProvider
     * @throws \Gen3se\Engine\Exception\Option\NotFound
     */
    public function shouldTakeAChoiceAndSelectARandomOption(Choice $choice)
    {
        $this
            ->object($this->newTestedInstance($choice))
            ->object($this->testedInstance->getPickedOption())
                ->isInstanceOf('Gen3se\Engine\Choice\Option')
            ->variable($choice->getOptionCollection()->get($this->testedInstance->getPickedOption()->getName()))
        ;
    }

    public function shouldThrowExceptionIfItsNotPossibleToResolve()
    {
        $this
            ->given(
                $optCollection = new Collection(),
                $optCollection->add(new Option('opt-name', 0)),
                $choice = new Choice('choice-1', $optCollection)
            )
            ->KapowException(function () use ($choice) {
                $this->newTestedInstance($choice);
            })
                ->hasKapowMessage('Cannot find options in collection at stack position "0" for choice-1')
                ->hasCode(Level::ERROR)
        ;
    }
}
