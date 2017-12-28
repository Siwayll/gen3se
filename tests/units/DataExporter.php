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
                $option = $choice->getOptionCollection()->findByPositonInStack(0),
                $this->newTestedInstance()
            )
            ->object($this->testedInstance->saveFor($choice, $option))
                ->isTestedInstance()
        ;
    }
}
