<?php

namespace Gen3se\Engine\Specs\Units;

use Gen3se\Engine\Choice\Choice;
use Gen3se\Engine\Tests\Units\Provider\SimpleChoiceTrait;
use Gen3se\Engine\Tests\Units\Test;

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
            $this->getEyeColorChoice()
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
                $anotherOption = $optCollection->findByPositonInStack($optCollection->getTotalWeight()),
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

            ->object($this->testedInstance->saveFor($choice, $anotherOption))
                ->isTestedInstance()
            ->array($this->testedInstance->get($choice->getName()))
                ->contains($option->exportCleanFields())
                ->contains($anotherOption->exportCleanFields())
                ->size->isEqualTo(3)


        ;
    }
}
