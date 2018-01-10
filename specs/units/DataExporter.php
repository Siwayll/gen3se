<?php

namespace Gen3se\Engine\Specs\Units;

use Gen3se\Engine\Choice\Choice;
use Gen3se\Engine\Option\Collection;
use Gen3se\Engine\Option\Option;
use Gen3se\Engine\Specs\Units\Provider\SimpleChoiceTrait;
use Gen3se\Engine\Specs\Units\Test;
use Siwayll\RumData\Converter\FromArray;

class DataExporter extends Test
{
    use SimpleChoiceTrait;

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
    public function shouldSaveResultDataForAnOption(Choice $choice): void
    {
        $this
            ->given(
                $optCollection = $choice->getOptionCollection(),
                $option = $optCollection->findByPositonInStack(0),
                $rumOption = FromArray::toRumData($option->exportCleanFields()),
                $this->newTestedInstance()
            )
            ->object($this->testedInstance->saveFor($choice, $option))
                ->isTestedInstance()
            ->object($this->testedInstance->get($choice->getName()))
            ->array((array) $this->testedInstance->get($choice->getName()))
                ->isEqualTo($option->exportCleanFields())
            ->dump($this->testedInstance->get($choice->getName()))
            ->object($this->testedInstance->saveFor($choice, $option))
                ->isTestedInstance()
            ->dump($this->testedInstance->get($choice->getName()))
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
                $option = $optCollection->findByPositonInStack(0),
                $anotherOption = $optCollection->findByPositonInStack($optCollection->getTotalWeight()),
                $this->newTestedInstance(),
                $this->testedInstance->saveFor($choice, $option)
            )
            ->object($this->testedInstance->get($choice->getName()))
                ->isEqualTo(FromArray::toRumData($option->exportCleanFields()))
            ->if($this->testedInstance->saveFor($choice, $anotherOption))
            ->array((array) $this->testedInstance->get($choice->getName()))
                ->variable[0]->isEqualTo(FromArray::toRumData($option->exportCleanFields()))
                ->variable[1]->isEqualTo(FromArray::toRumData($anotherOption->exportCleanFields()))
                ->size->isEqualTo(2)
        ;
    }

    public function shouldUseStorageRuleChoiceInstruction(): void
    {
        $this
            ->given(
                $storageRule = 'x.un.deux.trois',
                $option = new Option('opt1', 500),
                $option->set('data', 'toto'),
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
