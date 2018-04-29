<?php declare(strict_types = 1);

namespace Gen3se\Engine\Specs\Units\Result;

use Gen3se\Engine\Result;
use Gen3se\Engine\Specs\Units\Provider\Choice\Option as MockOptionProvider;
use Gen3se\Engine\Specs\Units\Provider\Result\Filer as MockFilerProvider;
use Gen3se\Engine\Specs\Units\Test;
use Siwayll\RumData\Converter\FromArray;

class Sample extends Test
{
    use MockFilerProvider;
    use MockOptionProvider;

    public function shouldImplementResultInterface(): void
    {
        $this
            ->testedClass
                ->hasInterface(Result::class)
        ;
    }

    public function shouldRegisterOptionData()
    {
        $this
            ->given(
                $filer = $this->createMockFiler('first', 'second'),
                $option = $this->createMockOption(
                    null,
                    null,
                    [
                        'key' => 'data',
                        'secondeKey' => \uniqid(),
                    ]
                ),
                $this->newTestedInstance()
            )
            ->if($this->testedInstance->registersTo($option, $filer))
            ->castToArray($this->testedInstance->dump())
                ->castToArray['first']
                    ->castToArray['second']->isEqualTo($option->dataToArray())
            ->if($this->testedInstance->registersTo($option, $filer))
            ->and($this->testedInstance->registersTo($option, $filer))
            ->castToArray($this->testedInstance->dump()['first']['second'])
                ->size->isEqualTo(3)
        ;
    }

    public function shouldNotRegisterEmptyData(): void
    {
        $this
            ->given(
                $option = $this->createMockOption(),
                $filer = $this->createMockFiler(),
                $this->newTestedInstance()
            )
            ->if($this->testedInstance->registersTo($option, $filer))
            ->mock($option)
                ->call('dataToArray')->once()
            ->castToArray($this->testedInstance->dump())
                ->isEmpty()
        ;
    }

    public function shouldCreateArrayForMultipleChoiceResults()
    {
        $this
            ->given(
                $filer = $this->createMockFiler(),
                $option = $this->createMockOption(
                    null,
                    null,
                    ['text' => \uniqid()]
                ),
                $anotherOption = $this->createMockOption(
                    null,
                    null,
                    ['text' => \uniqid()]
                ),
                $this->newTestedInstance()
            )
            ->if($this->testedInstance->registersTo($option, $filer))
            ->and($this->testedInstance->registersTo($anotherOption, $filer))
            ->castToArray($this->testedInstance->dump())
                ->variable[0]->isEqualTo(FromArray::toRumData($option->dataToArray()))
                ->variable[1]->isEqualTo(FromArray::toRumData($anotherOption->dataToArray()))
                ->size->isEqualTo(2)
        ;
    }
}
