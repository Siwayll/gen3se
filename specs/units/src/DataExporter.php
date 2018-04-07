<?php

namespace Gen3se\Engine\Specs\Units;

use Gen3se\Engine\Choice\Choice;
use Gen3se\Engine\Choice\Option\Collection;
use Gen3se\Engine\Choice\Option;
use Gen3se\Engine\Specs\Units\Provider\OptionTrait;
use Gen3se\Engine\Specs\Units\Provider\SimpleChoiceTrait;
use Gen3se\Engine\Specs\Units\Test;
use Siwayll\RumData\Converter\FromArray;

class DataExporter extends Test
{
    use SimpleChoiceTrait, OptionTrait;

    public function shouldImplementDataExporterInterface(): void
    {
        $this
            ->given($this->newTestedInstance)
            ->class(get_class($this->testedInstance))
                ->hasInterface('Gen3se\Engine\DataExporterInterface')
        ;
    }

    public function shouldImplementModInterface(): void
    {
        $this
            ->given($this->newTestedInstance)
            ->class(get_class($this->testedInstance))
                ->hasInterface('Gen3se\Engine\Mod\ModInterface')
        ;
    }

    protected function choiceProvider(): array
    {
        return [
            $this->getEyeColorChoice(),
            $this->getHairColorChoice()
        ];
    }

    /**
     * @dataProvider choiceProvider
     */
    public function shouldSaveResultDataForAChoice(Choice $choice): void
    {
        $this
            ->given(
                $optCollection = $choice->getOptionCollection(),
                $option = $optCollection->findByPositionInStack(0),
                $rumOption = FromArray::toRumData($option->dataToArray()),
                $this->newTestedInstance()
            )
            ->object($this->testedInstance->saveFor($choice, $option))
                ->isTestedInstance()
            ->object($this->testedInstance->get($choice->getName()))
            ->array((array) $this->testedInstance->get($choice->getName()))
                ->isEqualTo($option->dataToArray())
            ->object($this->testedInstance->saveFor($choice, $option))
                ->isTestedInstance()
            ->array((array) $this->testedInstance->get($choice->getName()))
                ->contains($rumOption)
                ->size->isEqualTo(2)
        ;
    }

    public function shouldNotSaveEmptyData(): void
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
     * @dataProvider choiceProvider
     */
    public function shouldCreateArrayForMultipleChoiceResults(Choice $choice): void
    {
        $this
            ->given(
                $optCollection = $choice->getOptionCollection(),
                $option = $optCollection->findByPositionInStack(0),
                $anotherOption = $optCollection->findByPositionInStack($optCollection->getTotalWeight()),
                $this->newTestedInstance(),
                $this->testedInstance->saveFor($choice, $option)
            )
            ->object($this->testedInstance->get($choice->getName()))
                ->isEqualTo(FromArray::toRumData($option->dataToArray()))
            ->if($this->testedInstance->saveFor($choice, $anotherOption))
            ->dump((array) $this->testedInstance->get($choice->getName()))
            ->array((array) $this->testedInstance->get($choice->getName()))
                ->variable[0]->isEqualTo(FromArray::toRumData($option->dataToArray()))
                ->variable[1]->isEqualTo(FromArray::toRumData($anotherOption->dataToArray()))
                ->size->isEqualTo(2)
        ;
    }

    public function shouldUseStorageRuleChoiceInstruction(): void
    {
        $this
            ->given(
                $storageRule = 'x.un.deux.trois',
                $option = new Option('opt1', 500),
                $option->add(new Option\Data\Simple('data', 'toto')),
                $optCollection = new Collection(),
                $optCollection->add($option),
                $choice = new Choice('choice-1', $optCollection),
                $choice->set('dataStorageRule', $storageRule),
                $this->newTestedInstance()
            )
            ->object($this->testedInstance->saveFor($choice, $option))
                ->isTestedInstance()
            ->object($this->testedInstance->get('un', 'deux', 'trois'))
                ->isEqualTo(FromArray::toRumData(['data' => 'toto']))
        ;
    }
}
