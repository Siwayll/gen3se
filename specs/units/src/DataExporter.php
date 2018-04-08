<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units;

use Gen3se\Engine\Choice;
use Gen3se\Engine\Choice\Option\Collection;
use Gen3se\Engine\Choice\Option;
use Gen3se\Engine\Mod\ModInterface;
use \Gen3se\Engine\Specs\Units\Provider\Choice as MockChoiceProvider;
use Gen3se\Engine\Specs\Units\Provider\OptionTrait;
use Gen3se\Engine\Specs\Units\Provider\SimpleChoiceTrait;
use Siwayll\RumData\Converter\FromArray;


class DataExporter extends Test
{
    use SimpleChoiceTrait, OptionTrait;
    use MockChoiceProvider;

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
                ->hasInterface(ModInterface::class)
        ;
    }

    protected function choiceProvider(): array
    {
        return [
            $this->getEyeColorChoice(),
            $this->getHairColorChoice()
        ];
    }

    public function shouldSaveResultDataForAChoice()
    {
        $this
            ->given(
                $choice = $this->createMockChoice(),
                $option = $this->createMockOption(
                    null,
                    null,
                    ['data']
                ),
                $this->newTestedInstance()
            )
            ->object($this->testedInstance->saveFor($choice, $option))
                ->isTestedInstance()
            ->object($this->testedInstance->get($choice->getName()))
            ->castToArray($this->testedInstance->get($choice->getName()))
                ->isEqualTo($option->dataToArray())
            ->object($this->testedInstance->saveFor($choice, $option))
                ->isTestedInstance()
            ->castToArray((array) $this->testedInstance->get($choice->getName()))
                ->size->isEqualTo(2)
        ;
    }

    public function shouldNotSaveEmptyData(): void
    {
        $this
            ->given(
                $option = $this->createMockOption(),
                $choice = $this->createMockChoice(),
                $this->newTestedInstance()
            )
            ->object($this->testedInstance->saveFor($choice, $option))
                ->isTestedInstance()
            ->variable($this->testedInstance->get($choice->getName()))
                ->isNull()
        ;
    }

    public function shouldCreateArrayForMultipleChoiceResults()
    {
        $this
            ->given(
                $choice = $this->createMockChoice(),
                $option = $this->createMockOption(
                    null,
                    null,
                    ['text' => uniqid()]
                ),
                $anotherOption = $this->createMockOption(
                    null,
                    null,
                    ['text' => uniqid()]
                ),
                $this->newTestedInstance()
            )
            ->if($this->testedInstance->saveFor($choice, $option))
            ->and($this->testedInstance->saveFor($choice, $anotherOption))
            ->dump((array) $this->testedInstance->get($choice->getName()))
            ->castToArray($this->testedInstance->get($choice->getName()))
                ->variable[0]->isEqualTo(FromArray::toRumData($option->dataToArray()))
                ->variable[1]->isEqualTo(FromArray::toRumData($anotherOption->dataToArray()))
                ->size->isEqualTo(2)
        ;
    }

    public function shouldUseStorageRuleChoiceInstruction(): void
    {
        $this
            ->skip('not implemented yet')
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
