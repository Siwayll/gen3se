<?php

namespace Gen3se\Engine\Specs\Units;

use Gen3se\Engine\Choice\Choice;
use Gen3se\Engine\Option\Collection;
use Gen3se\Engine\Option\Option;
use Gen3se\Engine\Specs\Units\Provider\SimpleChoiceTrait;
use Gen3se\Engine\Specs\Units\Test;

class DataExporter extends Test
{
    use SimpleChoiceTrait;

    public function shouldImplementDataExporterInterface()
    {
        $this
            ->given($this->newTestedInstance)
            ->class(get_class($this->testedInstance))
                ->hasInterface('Gen3se\Engine\DataExporterInterface')
        ;
    }

    protected function choiceProvider()
    {
        return [
            $this->getEyeColorChoice(),
            $this->getHairColorChoice()
        ];
    }

    /**
     * @param Choice $choice
     * @throws \Gen3se\Engine\Exception\Option\NotFoundInStack
     * @throws \Gen3se\Engine\Exception\Option\PositionMustBeRelevent
     * @dataProvider choiceProvider
     */
    public function shouldSaveResultDataForAnOption(Choice $choice)
    {
        $this
            ->given(
                $optCollection = $choice->getOptionCollection(),
                $option = $optCollection->findByPositonInStack(0),
                $this->newTestedInstance()
            )
            ->object($this->testedInstance->saveFor($choice, $option))
                ->isTestedInstance()
            ->array($this->testedInstance->get($choice->getName()))
                ->isEqualTo($option->exportCleanFields())
            ->object($this->testedInstance->saveFor($choice, $option))
                ->isTestedInstance()
            ->array($this->testedInstance->get($choice->getName()))
                ->contains($option->exportCleanFields())
                ->size->isEqualTo(2)
        ;
    }

    public function shouldNotSaveEmptyData()
    {
        $this
            ->given(
                $option = new Option('opt1', 500),
                $optCollection = new Collection(),
                $optCollection->add($option),
                $choice = new Choice('choice-1', $optCollection),
                $this->newTestedInstance()
            )
            ->object($this->testedInstance->saveFor($choice, $option))
                ->isTestedInstance()
            ->variable($this->testedInstance->get($choice->getName()))
                ->isNull()
        ;
    }

    /**
     * @param Choice $choice
     * @throws \Gen3se\Engine\Exception\Option\NotFoundInStack
     * @throws \Gen3se\Engine\Exception\Option\PositionMustBeRelevent
     * @dataProvider choiceProvider
     */
    public function shouldCreateArrayForMultipleChoiceResults(Choice $choice)
    {
        $this
            ->given(
                $optCollection = $choice->getOptionCollection(),
                $option = $optCollection->findByPositonInStack(0),
                $anotherOption = $optCollection->findByPositonInStack($optCollection->getTotalWeight()),
                $this->newTestedInstance(),
                $this->testedInstance->saveFor($choice, $option)
            )
            ->array($this->testedInstance->get($choice->getName()))
                ->isEqualTo($option->exportCleanFields())
            ->if($this->testedInstance->saveFor($choice, $anotherOption))
            ->dump($this->testedInstance->get($choice->getName()))
            ->array($this->testedInstance->get($choice->getName()))
                ->array[0]->isEqualTo($option->exportCleanFields())
                ->array[1]->isEqualTo($anotherOption->exportCleanFields())
                ->size->isEqualTo(2)

        ;
    }
}
